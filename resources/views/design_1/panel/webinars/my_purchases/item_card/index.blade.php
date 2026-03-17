@if($sale->type === \App\Models\Sale::$chapter && !empty($sale->chapter) && !empty($sale->chapter->webinar))
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
            // Match cart display: "Course Title — Section Title"
            'itemTitle' => $course->title . ' — ' . $chapter->title,
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
