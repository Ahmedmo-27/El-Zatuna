@if(!empty($sale->webinar))
    <a href="{{ $saleItem->getLearningPageUrl() }}" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
        <span class="font-12 text-primary mr-4">{{ trans('update.continue_learning') }}</span>
        <x-iconsax-lin-arrow-right class="icons text-primary mt-2" width="16px" height="16px"/>
    </a>
@elseif(!empty($sale->bundle))
    <a href="{{ $saleItem->getUrl() }}" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
        <span class="font-12 text-primary mr-4">{{ trans('update.details') }}</span>
        <x-iconsax-lin-arrow-right class="icons text-primary mt-2" width="16px" height="16px"/>
    </a>
@endif
