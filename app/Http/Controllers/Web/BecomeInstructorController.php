<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\Installment\InstallmentPlans;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\BecomeInstructor;
use App\Models\Category;
use App\Models\RegistrationPackage;
use App\Models\Translation\CategoryTranslation;
use App\Models\Role;
use App\Models\UserBank;
use App\Models\UserOccupation;
use App\Models\UserSelectedBank;
use App\Models\UserSelectedBankSpecification;
use App\Services\R2StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BecomeInstructorController extends Controller
{
    use InstallmentsTrait;
    use UserFormFieldsTrait;

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $occupations = $user->occupations->pluck('category_id')->toArray();

            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            $isOrganizationRole = (!empty($lastRequest) and $lastRequest->role == Role::$organization);
            $isInstructorRole = (empty($lastRequest) or $lastRequest->role == Role::$teacher);

            $userBanks = UserBank::query()
                ->with([
                    'specifications'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            $formFields = $this->getFormFieldsByUserType($request, 'become_instructor', true, null, $lastRequest);


            $data = [
                'pageTitle' => trans('site.become_instructor'),
                'user' => $user,
                'lastRequest' => $lastRequest,
                'categories' => $categories,
                'occupations' => $occupations,
                'isOrganizationRole' => $isOrganizationRole,
                'isInstructorRole' => $isInstructorRole,
                'userBanks' => $userBanks,
                'formFields' => $formFields,
                'becomeInstructorSettings' => getBecomeInstructorSettings(),
            ];

            return view('design_1.web.become_instructor.wizard.index', $data);
        }

        abort(404);
    }

    public function store(Request $request)
    {
        \Log::info('BecomeInstructorController::store - Method called', [
            'user_id' => auth()->id(),
            'has_certificate_file' => $request->hasFile('certificate'),
            'has_identity_scan_file' => $request->hasFile('identity_scan'),
            'all_files' => $request->allFiles(),
        ]);
        
        $user = auth()->user();

        if ($user->isUser()) {
            $hasLastRequest = BecomeInstructor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'accept'])
                ->first();

            $data = $request->all();
            
            \Log::info('BecomeInstructorController::store - Request data', [
                'user_id' => $user->id,
                'role' => $data['role'] ?? 'not_set',
                'has_certificate' => $request->hasFile('certificate'),
                'has_identity_scan' => $request->hasFile('identity_scan'),
            ]);

            // Build validation rules - certificate and identity_scan commented out in form, so both optional
            $rules = [
                'role' => 'required',
                'occupations' => 'required',
                'bank_id' => 'nullable', // Commented out in form, so making optional
                'description' => 'nullable|string',
                'certificate' => 'nullable',
                'identity_scan' => 'nullable',
            ];

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                \Log::warning('BecomeInstructorController::store - Validation failed', [
                    'user_id' => $user->id,
                    'errors' => $validate->errors()->toArray(),
                ]);
                $errors = $validate->errors();

                $type = ($data['role'] == "teacher") ? "become_instructor" : "become_organization";
                $form = $this->getFormFieldsByType($type);

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors->add($id, $error);
                        }
                    }
                }

                throw new ValidationException($validate);
            } else {
                $type = ($data['role'] == "teacher") ? "become_instructor" : "become_organization";
                $form = $this->getFormFieldsByType($type);
                $errors = [];

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors[$id] = $error;
                        }
                    }
                }

                if (count($errors)) {
                    \Log::warning('BecomeInstructorController::store - Form field errors', [
                        'user_id' => $user->id,
                        'errors' => $errors,
                    ]);
                    return back()->withErrors($errors)->withInput($request->all());
                }
            }

            \Log::info('BecomeInstructorController::store - Validation passed, proceeding to file uploads', [
                'user_id' => $user->id,
            ]);

            // Handle file uploads to R2
            \Log::info('BecomeInstructorController::store - Starting file upload handling', [
                'user_id' => $user->id,
                'has_certificate' => $request->hasFile('certificate'),
                'has_identity_scan' => $request->hasFile('identity_scan'),
            ]);
            
            $r2Service = new R2StorageService();
            $certificatePath = null;
            $identityScanPath = null;
            
            // Upload certificate if provided
            if ($request->hasFile('certificate')) {
                \Log::info('BecomeInstructorController::store - Processing certificate upload', [
                    'user_id' => $user->id,
                    'file_name' => $request->file('certificate')->getClientOriginalName(),
                    'file_size' => $request->file('certificate')->getSize(),
                ]);
                // Delete old certificate from R2 if it exists
                if (!empty($user->certificate) && strpos($user->certificate, 'Instructor-application/') === 0) {
                    try {
                        $r2Service->deleteFile($user->certificate);
                        \Log::info('Deleted old certificate from R2', [
                            'user_id' => $user->id,
                            'old_path' => $user->certificate,
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old certificate from R2', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                
                $certificateFile = $request->file('certificate');
                $userName = !empty($user->full_name) ? $user->full_name : 'user_' . $user->id;
                $result = $r2Service->uploadInstructorApplicationFile(
                    $certificateFile,
                    $user->id,
                    $userName,
                    'certificates'
                );
                
                if ($result['status']) {
                    $certificatePath = $result['path'];
                    \Log::info('Certificate uploaded to R2', [
                        'user_id' => $user->id,
                        'path' => $certificatePath,
                    ]);
                } else {
                    \Log::error('Failed to upload certificate to R2', [
                        'user_id' => $user->id,
                        'error' => $result['error'] ?? 'Unknown error',
                    ]);
                    return back()->withErrors(['certificate' => 'Failed to upload certificate: ' . ($result['error'] ?? 'Unknown error')])->withInput($request->all());
                }
            } elseif (!empty($data['certificate']) && is_string($data['certificate'])) {
                // Keep existing certificate path if it's a string (already uploaded)
                $certificatePath = $data['certificate'];
            } elseif (!empty($user->certificate)) {
                // Keep existing certificate from user if no new file uploaded
                $certificatePath = $user->certificate;
            }
            
            // Upload identity scan if provided
            if ($request->hasFile('identity_scan')) {
                \Log::info('BecomeInstructorController::store - Processing identity_scan upload', [
                    'user_id' => $user->id,
                    'file_name' => $request->file('identity_scan')->getClientOriginalName(),
                    'file_size' => $request->file('identity_scan')->getSize(),
                ]);
                // Delete old identity scan from R2 if it exists
                if (!empty($user->identity_scan) && strpos($user->identity_scan, 'Instructor-application/') === 0) {
                    try {
                        $r2Service->deleteFile($user->identity_scan);
                        \Log::info('Deleted old identity scan from R2', [
                            'user_id' => $user->id,
                            'old_path' => $user->identity_scan,
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old identity scan from R2', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                
                $identityScanFile = $request->file('identity_scan');
                $userName = !empty($user->full_name) ? $user->full_name : 'user_' . $user->id;
                $result = $r2Service->uploadInstructorApplicationFile(
                    $identityScanFile,
                    $user->id,
                    $userName,
                    'identity_scan'
                );
                
                if ($result['status']) {
                    $identityScanPath = $result['path'];
                    \Log::info('Identity scan uploaded to R2', [
                        'user_id' => $user->id,
                        'path' => $identityScanPath,
                    ]);
                } else {
                    \Log::error('Failed to upload identity scan to R2', [
                        'user_id' => $user->id,
                        'error' => $result['error'] ?? 'Unknown error',
                    ]);
                    return back()->withErrors(['identity_scan' => 'Failed to upload identity scan: ' . ($result['error'] ?? 'Unknown error')])->withInput($request->all());
                }
            } elseif (!empty($data['identity_scan']) && is_string($data['identity_scan'])) {
                // Keep existing identity scan path if it's a string (already uploaded)
                $identityScanPath = $data['identity_scan'];
            } elseif (!empty($user->identity_scan)) {
                // Keep existing identity scan from user if no new file uploaded
                $identityScanPath = $user->identity_scan;
            }

            $lastRequest = BecomeInstructor::query()->updateOrCreate([
                'user_id' => $user->id,
            ], [
                'role' => $data['role'],
                'certificate' => $certificatePath,
                'description' => $data['description'],
                'created_at' => time()
            ]);

            // Update user with file paths
            $userUpdateData = [];
            if ($identityScanPath !== null) {
                $userUpdateData['identity_scan'] = $identityScanPath;
            }
            if ($certificatePath !== null) {
                $userUpdateData['certificate'] = $certificatePath;
            }
            
            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }

            // Bank account section - only process if bank_id is provided (currently commented out in form)
            if (!empty($data['bank_id'])) {
                UserSelectedBank::query()->where('user_id', $user->id)->delete();
                $userSelectedBank = UserSelectedBank::query()->create([
                    'user_id' => $user->id,
                    'user_bank_id' => $data['bank_id']
                ]);

                if (!empty($data['bank_specifications'])) {
                    $specificationInsert = [];

                    foreach ($data['bank_specifications'] as $specificationId => $specificationValue) {
                        if (!empty($specificationValue)) {
                            $specificationInsert[] = [
                                'user_selected_bank_id' => $userSelectedBank->id,
                                'user_bank_specification_id' => $specificationId,
                                'value' => $specificationValue
                            ];
                        }
                    }

                    UserSelectedBankSpecification::query()->insert($specificationInsert);
                }
            }

            if (!empty($data['occupations'])) {
                UserOccupation::where('user_id', $user->id)->delete();

                foreach ($data['occupations'] as $item) {
                    $categoryId = $this->resolveOccupationCategoryId($item);
                    if ($categoryId) {
                        UserOccupation::create([
                            'user_id' => $user->id,
                            'category_id' => $categoryId
                        ]);
                    }
                }
            }


            if (empty($hasLastRequest)) {
                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
                ];
                sendNotification("new_become_instructor_request", $notifyOptions, 1);

            }

            if ((!empty(getRegistrationPackagesGeneralSettings('show_packages_during_registration')) and getRegistrationPackagesGeneralSettings('show_packages_during_registration'))) {
                return redirect(route('becomeInstructorPackages'));
            }

            // Extra Form
            $this->storeBecomeInstructorFormFields($data, $lastRequest);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('site.become_instructor_success_request'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function packages()
    {
        $user = auth()->user();

        $role = 'instructors';

        if (!empty($user) and $user->isUser()) {
            $becomeInstructor = BecomeInstructor::where('user_id', $user->id)->first();

            if (!empty($becomeInstructor) and $becomeInstructor->role == Role::$organization) {
                $role = 'organizations';
            }

            $packages = RegistrationPackage::where('role', $role)
                ->where('status', 'active')
                ->get();

            $userPackage = new UserPackage();
            $defaultPackage = $userPackage->getDefaultPackage($role);

            $data = [
                'pageTitle' => trans('update.registration_packages'),
                'packages' => $packages,
                'defaultPackage' => $defaultPackage,
                'becomeInstructor' => $becomeInstructor ?? null,
                'selectedRole' => $role
            ];

            return view('design_1.web.become_instructor.packages.index', $data);
        }

        abort(404);
    }

    public function checkPackageHasInstallment($id)
    {
        $user = auth()->user();

        if (!empty($user) and $user->isUser()) {
            $package = RegistrationPackage::where('id', $id)
                ->where('status', 'active')
                ->first();

            if (!empty($package) and $package->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('registration_packages', $package->id);

                return response()->json([
                    'has_installment' => (!empty($installments) and count($installments))
                ]);
            }
        }

        return response()->json([
            'has_installment' => false
        ]);
    }

    /**
     * Search subjects/categories by title (for become-instructor occupations select).
     * When q is empty, returns all subjects (up to 150) for initial display.
     */
    public function searchSubjects(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $locale = mb_strtolower(app()->getLocale());

        if ($q === '') {
            $categories = Category::query()
                ->orderBy('order')
                ->orderBy('id')
                ->limit(150)
                ->get();
            $results = $categories->map(function ($cat) {
                return ['id' => $cat->id, 'text' => $cat->title];
            })->values()->toArray();
            return response()->json(['results' => $results]);
        }

        $categoryIds = CategoryTranslation::where('locale', $locale)
            ->where('title', 'like', '%' . $q . '%')
            ->pluck('category_id')
            ->unique()
            ->values()
            ->toArray();

        if (empty($categoryIds)) {
            return response()->json(['results' => []]);
        }

        $categories = Category::whereIn('id', $categoryIds)->get();
        $results = $categories->map(function ($cat) {
            return ['id' => $cat->id, 'text' => $cat->title];
        })->values()->toArray();

        return response()->json(['results' => $results]);
    }

    /**
     * Create a new subject/category so it appears for future users (for become-instructor form).
     */
    public function createSubject(Request $request)
    {
        $request->validate(['title' => 'required|string|min:2|max:255']);
        $title = trim($request->input('title'));
        $locale = mb_strtolower(app()->getLocale());

        $existing = CategoryTranslation::where('locale', $locale)
            ->where('title', $title)
            ->first();
        if ($existing) {
            return response()->json(['id' => $existing->category_id, 'text' => $title]);
        }

        $order = Category::whereNull('parent_id')->max('order') + 1;
        $category = Category::create([
            'parent_id' => null,
            'slug' => Category::makeSlug($title),
            'order' => $order,
        ]);
        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => $locale,
            'title' => $title,
        ]);

        cache()->forget(Category::$cacheKey);

        return response()->json(['id' => $category->id, 'text' => $title]);
    }

    /**
     * Resolve occupation item to a category id: existing id (int) or create category from new title (string).
     */
    private function resolveOccupationCategoryId($item)
    {
        if (is_numeric($item)) {
            return (int) $item;
        }
        $title = trim((string) $item);
        if ($title === '') {
            return null;
        }
        $locale = mb_strtolower(app()->getLocale());
        $existing = CategoryTranslation::where('locale', $locale)->where('title', $title)->first();
        if ($existing) {
            return $existing->category_id;
        }
        $order = Category::whereNull('parent_id')->max('order') + 1;
        $category = Category::create([
            'parent_id' => null,
            'slug' => Category::makeSlug($title),
            'order' => $order,
        ]);
        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => $locale,
            'title' => $title,
        ]);
        cache()->forget(Category::$cacheKey);
        return $category->id;
    }
}
