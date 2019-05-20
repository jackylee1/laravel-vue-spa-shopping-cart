<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


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
 * @property string $user_name
 * @property string $user_surname
 * @property string $user_patronymic
 * @property int $discount
 * @property string $phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PromotionCodeUsageHistory[] $promotionalCodeUsage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User withCacheCooldownSeconds($seconds)
 */
	class User extends \Eloquent {}
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
 * App\Models\Favorite
 *
 * @property int $id
 * @property string|null $key
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FavoriteProduct[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUserId($value)
 * @mixin \Eloquent
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductSizeTable
 *
 * @property int $id
 * @property int $product_id
 * @property int $size_table_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereSizeTableId($value)
 * @mixin \Eloquent
 */
	class ProductSizeTable extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductMainType
 *
 * @property int $id
 * @property int $product_id
 * @property int $type_id
 * @property array $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereTypeId($value)
 * @mixin \Eloquent
 */
	class ProductMainType extends \Eloquent {}
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
 * @property int|null $show_on_index
 * @property int|null $show_on_footer
 * @property string|null $image_preview
 * @property string|null $image_origin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnIndex($value)
 */
	class Type extends \Eloquent {}
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
 * @property-read \App\Models\UserGroup $userGroup
 */
	class UserInGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderStatus
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $sorting_order
 * @property int $default
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereSortingOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus withCacheCooldownSeconds($seconds)
 */
	class OrderStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SizeTable
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $title
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereTitle($value)
 * @mixin \Eloquent
 */
	class SizeTable extends \Eloquent {}
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
 * App\Models\UtfRecord
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereSortingOrder($value)
 */
	class UtfRecord extends \Eloquent {}
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
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_available_id
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductAvailableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereQuantity($value)
 * @mixin \Eloquent
 * @property int $cart_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereCartId($value)
 * @property-read \App\Models\Product $product
 */
	class CartProduct extends \Eloquent {}
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
 * @property int|null $show_on_header
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereShowOnHeader($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderHistoryStatus
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_status_id
 * @property int $send_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\OrderStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereSendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus withCacheCooldownSeconds($seconds)
 */
	class OrderHistoryStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FavoriteProduct
 *
 * @property int $id
 * @property int $favorite_id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereFavoriteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereProductId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Product $product
 */
	class FavoriteProduct extends \Eloquent {}
}

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
 * App\Models\NovaPoshtaCity
 *
 * @property int $id
 * @property string $ref
 * @property string $area
 * @property string $settlement_type
 * @property string $city_id
 * @property string|null $description
 * @property string|null $settlement_type_description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NovaPoshtaWarehouse[] $warehouses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereSettlementType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaCity whereSettlementTypeDescription($value)
 * @mixin \Eloquent
 */
	class NovaPoshtaCity extends \Eloquent {}
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
 * @property-read \App\Models\Filter $filter
 */
	class TypeFilter extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TextBlockData[] $dataPage
 */
	class TextBlockTitle extends \Eloquent {}
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
 * @property-read \App\Models\ProductMainType $mainType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product allSelectAndCurrentPrice()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTypeAndCategory()
 * @property-read \App\Models\ProductSizeTable $sizeTable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product activeForPublic()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newProducts()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product searchByText()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product joinBestseller()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderProduct
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property float $price
 * @property float|null $discount_price
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereQuantity($value)
 * @mixin \Eloquent
 * @property int $product_available_id
 * @property float $product_price
 * @property float|null $product_discount_price
 * @property-read \App\Models\ProductAvailable $available
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductAvailableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct withCacheCooldownSeconds($seconds)
 */
	class OrderProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderPaymentMethod
 *
 * @property int $id
 * @property string $name
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereSortingOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod withCacheCooldownSeconds($seconds)
 */
	class OrderPaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NovaPoshtaArea
 *
 * @property int $id
 * @property string $ref
 * @property string $area_center
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NovaPoshtaCity[] $cities
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereAreaCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaArea whereRef($value)
 * @mixin \Eloquent
 */
	class NovaPoshtaArea extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string|null $user_surname
 * @property string|null $user_patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $note
 * @property int|null $order_status_id
 * @property int|null $delivery_method
 * @property int|null $payment_method_id
 * @property int|null $promotion_code_id
 * @property float|null $total_price
 * @property float|null $total_discount_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDeliveryMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserSurname($value)
 * @mixin \Eloquent
 * @property int|null $order_payment_method_id
 * @property int|null $promotional_code_id
 * @property int $read_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderHistoryStatus[] $historyStatuses
 * @property-read \App\Models\OrderPaymentMethod $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderProduct[] $products
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @property-read \App\Models\OrderStatus $status
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order withCacheCooldownSeconds($seconds)
 * @property string|null $area_id
 * @property string|null $city_id
 * @property string|null $warehouse_id
 * @property-read \App\Models\NovaPoshtaArea $npArea
 * @property-read \App\Models\NovaPoshtaCity $npCity
 * @property-read \App\Models\NovaPoshtaWarehouse $npWarehouse
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereWarehouseId($value)
 */
	class Order extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilterTree[] $filters
 */
	class ProductInFilter extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LinkToSocialNetwork
 *
 * @property int $id
 * @property string $url
 * @property string $image_preview
 * @property string $image_origin
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereUrl($value)
 * @mixin \Eloquent
 */
	class LinkToSocialNetwork extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductInFilterTree
 *
 * @property int $id
 * @property int $product_in_filter_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereProductInFilterId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree withCacheCooldownSeconds($seconds)
 */
	class ProductInFilterTree extends \Eloquent {}
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
 * App\Models\Cart
 *
 * @property int $id
 * @property string $key
 * @property int|null $user_id
 * @property int|null $user_promotional_code_id
 * @property string|null $user_name
 * @property string|null $user_surname
 * @property string|null $user_patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserPromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserSurname($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartProduct[] $products
 * @property int|null $order_payment_method_id
 * @property int|null $delivery
 * @property string|null $area_id
 * @property string|null $city_id
 * @property string|null $warehouse_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereOrderPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereWarehouseId($value)
 * @property string|null $note
 * @property-read \App\Models\NovaPoshtaArea $npArea
 * @property-read \App\Models\NovaPoshtaCity $npCity
 * @property-read \App\Models\NovaPoshtaWarehouse $npWarehouse
 * @property-read \App\Models\OrderPaymentMethod $paymentMethod
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereNote($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NovaPoshtaWarehouse
 *
 * @property int $id
 * @property string $ref
 * @property string $city_ref
 * @property string $site_key
 * @property string $type_of_warehouse
 * @property string $number
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereCityRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereSiteKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NovaPoshtaWarehouse whereTypeOfWarehouse($value)
 * @mixin \Eloquent
 */
	class NovaPoshtaWarehouse extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscribe
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $email
 * @property int $read_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereUpdatedAt($value)
 */
	class Subscribe extends \Eloquent {}
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
 * App\Models\PromotionCodeUsageHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory query()
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $user_id
 * @property int $promotional_code_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory withCacheCooldownSeconds($seconds)
 */
	class PromotionCodeUsageHistory extends \Eloquent {}
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
 * @property int|null $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereQuantity($value)
 */
	class ProductBestseller extends \Eloquent {}
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
 * App\Models\OrderProductFilterTree
 *
 * @property int $id
 * @property int $order_product_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereOrderProductId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree withCacheCooldownSeconds($seconds)
 */
	class OrderProductFilterTree extends \Eloquent {}
}

