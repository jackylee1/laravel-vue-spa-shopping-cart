@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php echo  '<yml_catalog date="'.\Carbon\Carbon::now()->toDateTimeString().'">'; @endphp
@php echo  '<shop>'; @endphp
@php echo  '<name>'.$name.'</name>'; @endphp
@php echo  '<company>'.$company.'</company>'; @endphp
@php echo  '<url>'.$url.'</url>'; @endphp
@php echo  '<currencies>'; @endphp
@php echo  '<currency id="UAH" rate="1"/>'; @endphp
@php echo  '</currencies>'; @endphp

@php echo  '<categories>'; @endphp
@foreach($types as $type)
    @php echo  '<category id="999' . $type->id . '">' . $type->name . '</category>'; @endphp
    @foreach($type->categories as $category)
        @php echo  '<category id="' . $category['id'] . '" parentId="999' . $type->id . '">' . $category['name'] . '</category>'; @endphp
        @foreach(collect($category['child']) as $subcategory)
            @php echo  '<category id="' . $subcategory['id'] . '" parentId="' . $category['id'] . '">' . $subcategory['name'] . '</category>'; @endphp
        @endforeach
    @endforeach
@endforeach
@php echo  '</categories>'; @endphp

@php echo  '<offers>'; @endphp
@foreach($products as $product)
    @php
        $category_id = null;
        $category = $product->mainType;
        if ($category !== null) {
            $category_id = $category->category_id[count($category->category_id) - 1];
        }
        $brand_filter = $filters->firstWhere('name', 'Бренд');
        $vendor_filter = $filters->firstWhere('name', 'Производитель');

        $vendor = null;

        if ($brand_filter !== null) {
            $product->filters->each(function ($item, $key) use ($filters, $brand_filter, &$vendor) {
                if ($item->filters->contains('filter_id', $brand_filter->id)) {
                    $vendor = $filters->firstWhere('id', $item->filters->last()->filter_id)->name;
                }
            });
        }
        elseif ($vendor_filter !== null && $vendor === null) {
            $product->filters->each(function ($item, $key) use ($filters, $vendor_filter, &$vendor) {
                if ($item->filters->contains('filter_id', $vendor_filter->id)) {
                    $vendor = $filters->firstWhere('id', $item->filters->last()->filter_id)->name;
                }
            });
        }

        $available = $product->available->where('quantity', '>', 0);
    @endphp

    @if($category_id !== null)
        @foreach ($available as $available_item)
            @php
                $name = preg_replace('/[^a-zA-Zа-яА-Я\d+]/ui', ' ', $product->name) . ' ';
                $available_item->filters->each(function ($filter) use ($filters, &$name) {
                    $name .= $filters->firstWhere('id', $filter->filter_id)->name . ' ';
                });
                $name .= " ({$product->article})";
            @endphp

            @php echo '<offer id="' . $available_item->id . $product->id . '" available="true">'; @endphp
            @php echo '<url>' . url("/product/{$product->slug}") . '</url>'; @endphp
            @php echo '<price>'. $product->current_price .'</price>'; @endphp
            @php echo '<currencyId>UAH</currencyId>'; @endphp
            @php echo '<categoryId>'. $category_id .'</categoryId>'; @endphp
            @foreach($product->images as $image)
                    @php echo '<picture>' . url('/app/public/images/products/' . $image->preview) . '</picture>'; @endphp
            @endforeach
            @if($vendor !== null)
                @php echo '<vendor>' . preg_replace('/[^a-zA-Zа-яА-Я\d+]/ui', ' ', $vendor) . '</vendor>'; @endphp
            @endif
            @php echo '<stock_quantity>' . '' .'</stock_quantity>'; @endphp
            @php echo '<name>' . $name . '</name>';
            @endphp
            @php echo '<description>'; @endphp
                <![CDATA[{!! $product->description !!}]]>
            @php echo '</description>'; @endphp

            @php echo '<param name="Артикул">' . $product->article . '</param>'; @endphp
            @php echo '<param name="Категория">' . $categories->firstWhere('id', $category_id)->name . '</param>'; @endphp

            @foreach ($available_item->filters as $available_filter)
                @php
                    $filter = $filters->firstWhere('id', $available_filter->filter_id);
                    $parent_filter = $filters->firstWhere('id', $filter->parent_id);
                @endphp
                @php echo '<param name="' . $parent_filter->name . '">' . $filter->name . '</param>'; @endphp
            @endforeach

            @php echo '</offer>'; @endphp
        @endforeach
    @endif
@endforeach
@php echo  '</offers>'; @endphp
@php echo  '</shop>'; @endphp
@php echo  '</yml_catalog>'; @endphp