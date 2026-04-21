@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle ?? trans('admin/main.bulk_discount_title') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/financial/discounts">{{ trans('admin/main.discounts') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.bulk_discount_title') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ trans('admin/main.bulk_discount_description') ?? 'Create Seasonal or Platform-Wide Discount' }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-7">
                            <form action="{{ getAdminPanelUrl() }}/financial/discounts/bulk/store" method="POST">
                                {{ csrf_field() }}

                                <!-- Title -->
                                <div class="form-group">
                                    <label>{{ trans('admin/main.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}"
                                           placeholder="e.g., Black Friday 2024"/>
                                    <small class="text-muted">{{ trans('admin/main.discount_title_hint') ?? 'A descriptive name for this promotional discount' }}</small>
                                    @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Discount Type -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('update.discount_type') }} <span class="text-danger">*</span></label>
                                            <select name="discount_type" class="js-discount-type form-control @error('discount_type') is-invalid @enderror">
                                                <option value="percentage" selected>{{ trans('admin/main.percentage') }}</option>
                                                <option value="fixed_amount">{{ trans('update.fixed_amount') }}</option>
                                            </select>
                                            @error('discount_type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Source -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="input-label d-block">{{ trans('update.source') }} <span class="text-danger">*</span></label>
                                            <select name="source" class="js-discount-source form-control @error('source') is-invalid @enderror">
                                                <option value="all" selected>{{ trans('update.discount_source_all') }}</option>
                                                <option value="course">{{ trans('update.discount_source_course') }}</option>
                                                <option value="bundle">{{ trans('update.discount_source_bundle') }}</option>
                                                <option value="category">{{ trans('update.discount_source_category') }}</option>
                                                <option value="product">{{ trans('update.discount_source_product') }}</option>
                                            </select>
                                            @error('source')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Apply To Items Section -->
                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.apply_to') }} <span class="text-danger">*</span></label>
                                    <p class="text-muted small">{{ trans('admin/main.apply_discount_hint') ?? 'Select what this discount applies to automatically' }}</p>
                                    <div class="custom-switches-stacked">
                                        <label class="custom-switch pl-0">
                                            <input type="radio" name="apply_to_items" value="all" class="custom-switch-input" checked/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer">
                                                {{ trans('admin/main.all') }} 
                                                <span class="text-muted small">({{ trans('admin/main.courses') }}, {{ trans('update.bundles') }}, {{ trans('admin/main.categories') }})</span>
                                            </label>
                                        </label>
                                        
                                        <label class="custom-switch pl-0">
                                            <input type="radio" name="apply_to_items" value="courses" class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer">{{ trans('admin/main.courses') }}</label>
                                        </label>

                                        <label class="custom-switch pl-0">
                                            <input type="radio" name="apply_to_items" value="bundles" class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer">{{ trans('update.bundles') }}</label>
                                        </label>

                                        <label class="custom-switch pl-0">
                                            <input type="radio" name="apply_to_items" value="categories" class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer">{{ trans('admin/main.categories') }}</label>
                                        </label>

                                        <label class="custom-switch pl-0">
                                            <input type="radio" name="apply_to_items" value="products" class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer">{{ trans('update.products') }}</label>
                                        </label>
                                    </div>
                                    @error('apply_to_items')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Discount Value Section -->
                                <div class="alert alert-info" role="alert">
                                    <i class="fa fa-info-circle"></i> {{ trans('admin/main.provide_discount_value') ?? 'Provide either a percentage or fixed amount' }}
                                </div>

                                <div class="row">
                                    <!-- Percentage -->
                                    <div class="col-md-6">
                                        <div class="form-group js-percentage-inputs">
                                            <label>{{ trans('admin/main.discount_percentage') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-percentage"></i>
                                                    </div>
                                                </div>
                                                <input type="number" name="percent"
                                                       class="form-control text-center @error('percent') is-invalid @enderror"
                                                       value="{{ old('percent') }}"
                                                       min="0"
                                                       max="100"
                                                       step="0.01"
                                                       placeholder="e.g., 10"/>
                                                @error('percent')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fixed Amount -->
                                    <div class="col-md-6">
                                        <div class="form-group js-fixed-amount-inputs d-none">
                                            <label>{{ trans('admin/main.amount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        {{ $currency ?? 'USD' }}
                                                    </div>
                                                </div>
                                                <input type="number" name="amount"
                                                       class="form-control text-center @error('amount') is-invalid @enderror"
                                                       value="{{ old('amount') }}"
                                                       min="0"
                                                       step="0.01"
                                                       placeholder="e.g., 10.00"/>
                                                @error('amount')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Max Amount (for percentage discounts) -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group js-percentage-inputs">
                                            <label>{{ trans('admin/main.max_amount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        {{ $currency ?? 'USD' }}
                                                    </div>
                                                </div>
                                                <input type="number" name="max_amount"
                                                       class="form-control text-center @error('max_amount') is-invalid @enderror"
                                                       value="{{ old('max_amount') }}"
                                                       min="0"
                                                       step="0.01"
                                                       placeholder="{{ trans('update.discount_max_amount_placeholder') }}"/>
                                                @error('max_amount')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="text-muted">{{ trans('admin/main.max_discount_amount_hint') ?? 'Maximum discount amount per transaction' }}</small>
                                        </div>
                                    </div>

                                    <!-- Minimum Order -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans('update.minimum_order') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        {{ $currency ?? 'USD' }}
                                                    </div>
                                                </div>
                                                <input type="number" name="minimum_order"
                                                       class="form-control text-center @error('minimum_order') is-invalid @enderror"
                                                       value="{{ old('minimum_order') }}"
                                                       min="0"
                                                       step="0.01"
                                                       placeholder="{{ trans('update.discount_minimum_order_placeholder') }}"/>
                                                @error('minimum_order')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Usage Limit -->
                                <div class="form-group">
                                    <label>{{ trans('admin/main.usable_times') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <input type="number" name="count"
                                               class="form-control text-center @error('count') is-invalid @enderror"
                                               value="{{ old('count') }}"
                                               min="0"
                                               placeholder="{{ trans('admin/main.count_placeholder') ?? '0 for unlimited' }}"/>
                                        @error('count')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">{{ trans('admin/main.leave_blank_unlimited') ?? 'Leave blank or 0 for unlimited usage' }}</small>
                                </div>

                                <!-- Discount Code -->
                                <div class="form-group">
                                    <label class="control-label">{{ trans('admin/main.discount_code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code"
                                           value="{{ old('code') }}"
                                           class="form-control text-center @error('code') is-invalid @enderror"
                                           placeholder="e.g., BLACKFRIDAY2024">
                                    @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="text-gray-500 text-small mt-1">{{ trans('admin/main.discount_code_hint') }}</div>
                                </div>

                                <!-- Expiration Date -->
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.expiration') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="dateRangeLabel">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="expired_at" class="form-control datetimepicker @error('expired_at') is-invalid @enderror"
                                               aria-describedby="dateRangeLabel" autocomplete="off"
                                               placeholder="{{ now()->format('Y-m-d H:i') }}"/>
                                        @error('expired_at')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> {{ trans('admin/main.create_bulk_discount') ?? 'Create Bulk Discount' }}
                                    </button>
                                    <a href="{{ getAdminPanelUrl() }}/financial/discounts" class="btn btn-secondary">
                                        {{ trans('admin/main.cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Helpful Info Sidebar -->
                        <div class="col-12 col-md-4 col-lg-5">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ trans('admin/main.bulk_discount_info') ?? 'About Bulk Discounts' }}</h5>
                                    <ul class="text-small">
                                        <li><strong>{{ trans('admin/main.all') }}:</strong> {{ trans('admin/main.apply_all_items_hint') ?? 'Applies to all courses, bundles, and categories automatically' }}</li>
                                        <li><strong>{{ trans('admin/main.courses') }}:</strong> {{ trans('admin/main.apply_courses_only_hint') ?? 'Applies to all active courses' }}</li>
                                        <li><strong>{{ trans('update.bundles') }}:</strong> {{ trans('admin/main.apply_bundles_only_hint') ?? 'Applies to all active bundles' }}</li>
                                        <li><strong>{{ trans('admin/main.categories') }}:</strong> {{ trans('admin/main.apply_categories_only_hint') ?? 'Applies to all active categories' }}</li>
                                    </ul>
                                    <hr>
                                    <h6>{{ trans('admin/main.example') ?? 'Example' }}</h6>
                                    <p class="text-small">{{ trans('admin/main.bulk_discount_example') ?? 'To create a 10% off everything sale during holidays:' }}</p>
                                    <ul class="text-small">
                                        <li>{{ trans('admin/main.title') }}: Holiday Sale 2024</li>
                                        <li>{{ trans('update.discount_type') }}: Percentage</li>
                                        <li>{{ trans('admin/main.apply_to') }}: All</li>
                                        <li>{{ trans('admin/main.discount_percentage') }}: 10</li>
                                        <li>{{ trans('admin/main.discount_code') }}: HOLIDAY2024</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/discount.min.js"></script>
    <script>
        // Handle discount type change (percentage vs fixed amount)
        document.querySelector('.js-discount-type')?.addEventListener('change', function() {
            const isPercentage = this.value === 'percentage';
            document.querySelectorAll('.js-percentage-inputs').forEach(el => {
                el.classList.toggle('d-none', !isPercentage);
            });
            document.querySelectorAll('.js-fixed-amount-inputs').forEach(el => {
                el.classList.toggle('d-none', isPercentage);
            });
        });

        // Trigger initial state
        document.querySelector('.js-discount-type')?.dispatchEvent(new Event('change'));
    </script>
@endpush
