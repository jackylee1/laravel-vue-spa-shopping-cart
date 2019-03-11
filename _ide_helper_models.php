<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\UserGroup
 *
 * @property int $id
 * @property string $name
 * @property string $like_name
 * @property int $discount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserInGroup[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup withCacheCooldownSeconds($seconds)
 */
	class UserGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductImage
 *
 * @property int $id
 * @property int $product_id
 * @property int $sorting_order
 * @property string $origin
 * @property string $preview
 * @property int|null $main_status
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereMainStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage wherePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereSortingOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage withCacheCooldownSeconds($seconds)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserPromotionalCode
 *
 * @property int $id
 * @property int $user_id
 * @property int $promotional_code_id
 * @property int $send_status_to_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereSendStatusToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode withCacheCooldownSeconds($seconds)
 */
	class UserPromotionalCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TextBlockTitle
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereTitle($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle withCacheCooldownSeconds($seconds)
 */
	class TextBlockTitle extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserInGroup
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_group_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereUserGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup withCacheCooldownSeconds($seconds)
 */
	class UserInGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TypeFilter
 *
 * @property int $id
 * @property int $type_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter whereTypeId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeFilter withCacheCooldownSeconds($seconds)
 */
	class TypeFilter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sorting_order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TypeFilter[] $filters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereSortingOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type withCacheCooldownSeconds($seconds)
 */
	class Type extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderProduct
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductId($value)
 * @mixin \Eloquent
 */
	class OrderProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Slider
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $url
 * @property string $image_origin
 * @property string $image_preview
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider withCacheCooldownSeconds($seconds)
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PromotionalCode
 *
 * @property int $id
 * @property string $code
 * @property string $like_code
 * @property int $discount
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserPromotionalCode $userPromotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereLikeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode withCacheCooldownSeconds($seconds)
 */
	class PromotionalCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TextBlockData
 *
 * @property int $id
 * @property int $text_block_title_id
 * @property string $title
 * @property int $type
 * @property string|null $url
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereTextBlockTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereUrl($value)
 * @mixin \Eloquent
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereSortingOrder($value)
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData withCacheCooldownSeconds($seconds)
 */
	class TextBlockData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Filter
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $like_name
 * @property string $slug
 * @property int $type
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereType($value)
 * @mixin \Eloquent
 * @property int $show_on_index
 * @property int|null $show_on_header
 * @property int|null $show_on_footer
 * @property int|null $type_index
 * @property string|null $image_origin
 * @property string|null $image_preview
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereTypeIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter withCacheCooldownSeconds($seconds)
 */
	class Filter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductAvailableFilter
 *
 * @property int $id
 * @property int $product_available_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereProductAvailableId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter withCacheCooldownSeconds($seconds)
 */
	class ProductAvailableFilter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductInFilterCategory
 *
 * @property int $id
 * @property int $product_in_filter_id
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereProductInFilterId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory withCacheCooldownSeconds($seconds)
 */
	class ProductInFilterCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $status
 * @property int $category
 * @property string|null $one_click_tel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOneClickTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $article
 * @property string $slug
 * @property string $name
 * @property string $like_name
 * @property string $description
 * @property string $preview_description
 * @property string $like_preview_description
 * @property float $price
 * @property int|null $discount
 * @property int|null $main_image
 * @property int $status
 * @property string|null $date_inclusion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductBestseller $bestseller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInCategory[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilter[] $filters
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductImage[] $images
 * @property-read \App\Models\ProductImage $mainImage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDateInclusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereLikePreviewDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePreviewDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSharePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product withCacheCooldownSeconds($seconds)
 * @property float|null $discount_price
 * @property string|null $discount_start
 * @property string|null $discount_end
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAvailable[] $available
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountStart($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductInFilter
 *
 * @property int $id
 * @property int $product_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereProductId($value)
 * @mixin \Eloquent
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereCategoryId($value)
 * @property int|null $type_id
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Filter $filter
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereTypeId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilterCategory[] $categories
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter withCacheCooldownSeconds($seconds)
 */
	class ProductInFilter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryFilter
 *
 * @property int $id
 * @property int $category_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter withCacheCooldownSeconds($seconds)
 */
	class CategoryFilter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductBestseller
 *
 * @property int $id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereProductId($value)
 * @mixin \Eloquent
 */
	class ProductBestseller extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PromotionCodeUsageHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory query()
 * @mixin \Eloquent
 */
	class PromotionCodeUsageHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductAvailable
 *
 * @property int $id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable whereProductId($value)
 * @mixin \Eloquent
 * @property int $quantity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAvailableFilter[] $filters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable withCacheCooldownSeconds($seconds)
 */
	class ProductAvailable extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $type_id
 * @property string $name
 * @property string $like_name
 * @property string $slug
 * @property int $sorting_order
 * @property int $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CategoryFilter[] $filters
 * @property-read \App\Models\Type $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereTypeId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category withCacheCooldownSeconds($seconds)
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string|null $description
 * @property int $reliability
 * @property string $like_name
 * @property string $like_email
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserInGroup $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserPromotionalCode[] $promotionalCodes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLikeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReliability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

