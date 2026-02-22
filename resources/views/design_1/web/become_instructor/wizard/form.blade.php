<div class="">
    <h3 class="font-24 font-weight-bold text-[#072923]">{{ trans('update.information') }} 🎓</h3>
    <div class="mt-8 text-[#072923]/70">{{ trans('update.become_instructor_organization_page_information_form_hint') }}</div>

    {{-- Role Selection --}}
    <div class="form-group mt-20">
        <label class="font-12 font-weight-bold text-[#072923]">Select Account Type</label>

        <div class="d-flex align-items-center gap-5 p-4 border-[#ECF4B8] rounded-12 mt-8 bg-[#F5F9E8]/30">
            <div class="custom-input-button custom-input-button-none-border-and-active-bg position-relative flex-1">
                <input type="radio" class="" name="role" id="role_teacher" value="teacher" checked/>
                <label for="role_teacher" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-[#072923] hover:text-[#C8CD06] transition-colors cursor-pointer">
                    {{ trans("update.instructor") }}
                </label>
            </div>

            <div class="custom-input-button custom-input-button-none-border-and-active-bg position-relative flex-1">
                <input type="radio" class="" name="role" id="role_organization" value="organization">
                <label for="role_organization" class="position-relative d-flex-center flex-column p-12 rounded-8 text-center text-[#072923] hover:text-[#C8CD06] transition-colors cursor-pointer">
                    {{ trans("update.organization") }}
                </label>
            </div>
        </div>

        @error('role')
        <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
        @enderror
    </div>

    {{-- Areas of Expertise: text field with search + "Add as new subject" option --}}
    @php
        $occupationsInitial = [];
        if (!empty($occupations) && !empty($categories)) {
            foreach ($categories as $cat) {
                if (!empty($cat->subCategories) && count($cat->subCategories)) {
                    foreach ($cat->subCategories as $sub) {
                        if (in_array($sub->id, $occupations)) {
                            $occupationsInitial[] = ['id' => $sub->id, 'text' => $sub->title];
                        }
                    }
                } else {
                    if (in_array($cat->id, $occupations)) {
                        $occupationsInitial[] = ['id' => $cat->id, 'text' => $cat->title];
                    }
                }
            }
        }
    @endphp
    <div class="form-group js-occupations-wrapper" data-initial="{{ e(json_encode($occupationsInitial)) }}">
        <p class="text-sm text-[#072923]/60 mb-2">Select the subjects or topics you want to teach. Type to search existing ones.</p>

        <div class="position-relative">
            <input type="text" id="occupationsInput" class="form-control border-[#ECF4B8] focus:border-[#C8CD06] focus:ring-[#C8CD06] js-occupations-input" placeholder="Type a subject name..." autocomplete="off">

            <div class="js-occupations-dropdown position-absolute bg-white border border-[#ECF4B8] rounded-12 shadow-sm mt-1 d-none" style="top: 100%; left: 0; right: 0; max-height: 220px; overflow-y: auto; z-index: 1050;">
                <div class="js-occupations-results p-2"></div>
                <div class="js-occupations-add-new border-top border-[#ECF4B8] p-2 text-[#072923]/70 cursor-pointer hover:bg-[#F5F9E8]/50" style="font-size: 13px;">
                    <span class="js-add-new-text">Add different subject</span> – <span class="js-add-new-term font-weight-medium text-[#C8CD06]"></span>
                </div>
            </div>
        </div>

        <div class="js-occupations-tags mt-8 d-flex flex-wrap gap-2" style="min-height: 24px;"></div>

        <div class="js-occupations-hidden-container"></div>

        @error('occupations')
        <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
        @enderror
    </div>

    {{-- Documents Section --}}
    <h5 class="font-12 mt-24 text-[#072923] font-weight-bold">Required Documents</h5>

    {{-- Bank Account Section - Commented Out --}}
    {{--
    <div class="form-group  mt-24">
        <label class="form-group-label">{{ trans('update.select_account_type') }}</label>

        <select name="bank_id" class="js-user-bank-input form-control select2 @error('bank_id')  is-invalid @enderror" data-minimum-results-for-search="Infinity">
            <option selected disabled>{{ trans('financial.select_account_type') }}</option>

            @foreach($userBanks as $userBank)
                <option value="{{ $userBank->id }}" @if(!empty($user) and !empty($user->selectedBank) and $user->selectedBank->user_bank_id == $userBank->id) selected="selected" @endif data-specifications="{{ json_encode($userBank->specifications->pluck('name','id')->toArray()) }}">{{ $userBank->title }}</option>
            @endforeach
        </select>
        @error('bank_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="js-bank-specifications-card">
        @if(!empty($user) and !empty($user->selectedBank) and !empty($user->selectedBank->bank))
            @foreach($user->selectedBank->bank->specifications as $specification)
                @php
                    $selectedBankSpecification = $user->selectedBank->specifications->where('user_selected_bank_id', $user->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                @endphp
                <div class="form-group">
                    <label class="form-group-label">{{ $specification->name }}</label>
                    <input type="text" name="bank_specifications[{{ $specification->id }}]" value="{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}" class="form-control"/>
                </div>
            @endforeach
        @endif
    </div>
    --}}


    {{-- Certificate upload - commented out to allow form submission without filling --}}
    {{--
    <div class="form-group custom-input-file flex-1 mt-24">
        <p class="text-sm text-[#072923]/60 mb-2">Upload your professional certificates and credentials (optional)</p>

        <div class="custom-file bg-[#F5F9E8] border border-[#ECF4B8] rounded-12 js-ajax-certificate hover:border-[#C8CD06] transition-colors">
            <input type="file" name="certificate" class="custom-file-input js-ajax-upload-file-input" id="certificatesInput" data-upload-name="certificate" accept=".pdf,.jpg,.jpeg,.png">
            <span class="custom-file-text text-[#072923]/70"></span>
            <label class="custom-file-label bg-transparent cursor-pointer" for="certificatesInput">
                <x-iconsax-lin-export class="icons text-[#072923]/60 hover:text-[#C8CD06] transition-colors" width="24px" height="24px"/>
            </label>
        </div>

        @error('certificate')
        <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
        @enderror
    </div>


    <div class="form-group custom-input-file flex-1 mt-24">
        <p class="text-sm text-[#072923]/60 mt-1 mb-2">Upload a scanned copy of your ID card or passport (required)</p>

        <div class="custom-file bg-[#F5F9E8] border border-[#ECF4B8] rounded-12 js-ajax-identity_scan hover:border-[#C8CD06] transition-colors">
            <input type="file" name="identity_scan" class="custom-file-input js-ajax-upload-file-input" id="identity_scansInput" data-upload-name="identity_scan" accept=".pdf,.jpg,.jpeg,.png">
            <span class="custom-file-text text-[#072923]/70"></span>
            <label class="custom-file-label bg-transparent cursor-pointer" for="identity_scansInput">
                <x-iconsax-lin-export class="icons text-[#072923]/60 hover:text-[#C8CD06] transition-colors" width="24px" height="24px"/>
            </label>
        </div>

        @error('identity_scan')
        <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
        @enderror
    </div>
    --}}

    <div class="form-group mt-24">
        <p class="text-sm text-[#072923]/60 mb-2">Tell us about yourself, your experience, and qualifications</p>
        <textarea name="description" rows="6" class="form-control border-[#ECF4B8] focus:border-[#C8CD06] focus:ring-[#C8CD06] text-[#072923] placeholder:text-[#072923]/40" placeholder="Describe your teaching experience, qualifications, and what makes you a great instructor...">{{ !empty($lastRequest) ? $lastRequest->description : old('description') }}</textarea>

        @error('description')
        <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <div class="js-form-fields-card">
        @if(!empty($formFields))
            {!! $formFields !!}
        @endif
    </div>

</div>
