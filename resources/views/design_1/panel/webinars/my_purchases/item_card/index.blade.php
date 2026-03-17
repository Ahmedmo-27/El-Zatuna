@php
    // If the user purchased a specific section (chapter), show the section name + course name.
    $isChapterPurchase = empty($sale->webinar) && !empty($sale->chapter) && !empty($sale->chapter->webinar) && $sale->type === \App\Models\Sale::$chapter;
@endphp

@if($isChapterPurchase)
    @php
        $course = $sale->chapter->webinar;
        $chapter = $sale->chapter;
    @endphp

    <div class="panel-course-card panel-course-card--section d-flex flex-column h-100">
        @include('design_1.panel.includes.course_card', [
            'item' => $course,
            'saleItem' => $course,
            'sale' => $sale,
            'badgesView' => 'design_1.panel.webinars.my_purchases.item_card.badges',
            'statsView' => 'design_1.panel.webinars.my_purchases.item_card.stats',
            'progressView' => 'design_1.panel.webinars.my_purchases.item_card.progress_and_chart',
            'footerRightView' => 'design_1.panel.webinars.my_purchases.item_card.footer_right',
            'actionsView' => 'design_1.panel.webinars.my_purchases.item_card.actions_dropdown',
            'actionsOutsideLink' => false,
            'wrapInLink' => false,
            'statsLinkUrl' => $course->getUrl(),
            // Extra context: show purchased section name under the title
            'extraTitleLine' => trans('update.section_title') . ': ' . $chapter->title,
        ])
    </div>
@else
    @php
        $saleItem = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;
    @endphp

    @if(!empty($saleItem))
        @include('design_1.panel.includes.course_card', [
            'item' => $saleItem,
            'saleItem' => $saleItem,
            'sale' => $sale,
            'badgesView' => 'design_1.panel.webinars.my_purchases.item_card.badges',
            'statsView' => 'design_1.panel.webinars.my_purchases.item_card.stats',
            'progressView' => 'design_1.panel.webinars.my_purchases.item_card.progress_and_chart',
            'footerRightView' => 'design_1.panel.webinars.my_purchases.item_card.footer_right',
            'actionsView' => 'design_1.panel.webinars.my_purchases.item_card.actions_dropdown',
            'actionsOutsideLink' => false,
            'wrapInLink' => false,
            'statsLinkUrl' => $saleItem->getUrl(),
        ])
    @endif
@endif
