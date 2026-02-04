@php
    $saleItem = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

    $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
    $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
    $isProgressing = false;

    if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing = true;
    }
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
