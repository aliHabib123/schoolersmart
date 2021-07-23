<?php
	//include all DAO files
	require_once('class/sql/Connection.class.php');
	require_once('class/sql/ConnectionFactory.class.php');
	require_once('class/sql/ConnectionProperty.class.php');
	require_once('class/sql/QueryExecutor.class.php');
	require_once('class/sql/Transaction.class.php');
	require_once('class/sql/SqlQuery.class.php');
	require_once('class/core/ArrayList.class.php');
	require_once('class/dao/DAOFactory.class.php');
 	
	require_once('class/dao/AlbumDAO.class.php');
	require_once('class/dto/Album.class.php');
	require_once('class/mysql/AlbumMySqlDAO.class.php');
	require_once('class/mysql/ext/AlbumMySqlExtDAO.class.php');
	require_once('class/dao/AttachmentDAO.class.php');
	require_once('class/dto/Attachment.class.php');
	require_once('class/mysql/AttachmentMySqlDAO.class.php');
	require_once('class/mysql/ext/AttachmentMySqlExtDAO.class.php');
	require_once('class/dao/BannerDAO.class.php');
	require_once('class/dto/Banner.class.php');
	require_once('class/mysql/BannerMySqlDAO.class.php');
	require_once('class/mysql/ext/BannerMySqlExtDAO.class.php');
	require_once('class/dao/BannerImageDAO.class.php');
	require_once('class/dto/BannerImage.class.php');
	require_once('class/mysql/BannerImageMySqlDAO.class.php');
	require_once('class/mysql/ext/BannerImageMySqlExtDAO.class.php');
	require_once('class/dao/BrandCategoryMappingDAO.class.php');
	require_once('class/dto/BrandCategoryMapping.class.php');
	require_once('class/mysql/BrandCategoryMappingMySqlDAO.class.php');
	require_once('class/mysql/ext/BrandCategoryMappingMySqlExtDAO.class.php');
	require_once('class/dao/BrandTypeDAO.class.php');
	require_once('class/dto/BrandType.class.php');
	require_once('class/mysql/BrandTypeMySqlDAO.class.php');
	require_once('class/mysql/ext/BrandTypeMySqlExtDAO.class.php');
	require_once('class/dao/CartDAO.class.php');
	require_once('class/dto/Cart.class.php');
	require_once('class/mysql/CartMySqlDAO.class.php');
	require_once('class/mysql/ext/CartMySqlExtDAO.class.php');
	require_once('class/dao/CityDAO.class.php');
	require_once('class/dto/City.class.php');
	require_once('class/mysql/CityMySqlDAO.class.php');
	require_once('class/mysql/ext/CityMySqlExtDAO.class.php');
	require_once('class/dao/ContentDAO.class.php');
	require_once('class/dto/Content.class.php');
	require_once('class/mysql/ContentMySqlDAO.class.php');
	require_once('class/mysql/ext/ContentMySqlExtDAO.class.php');
	require_once('class/dao/CountryDAO.class.php');
	require_once('class/dto/Country.class.php');
	require_once('class/mysql/CountryMySqlDAO.class.php');
	require_once('class/mysql/ext/CountryMySqlExtDAO.class.php');
	require_once('class/dao/DeliveryDAO.class.php');
	require_once('class/dto/Delivery.class.php');
	require_once('class/mysql/DeliveryMySqlDAO.class.php');
	require_once('class/mysql/ext/DeliveryMySqlExtDAO.class.php');
	require_once('class/dao/DeliveryStateDAO.class.php');
	require_once('class/dto/DeliveryState.class.php');
	require_once('class/mysql/DeliveryStateMySqlDAO.class.php');
	require_once('class/mysql/ext/DeliveryStateMySqlExtDAO.class.php');
	require_once('class/dao/ImageDAO.class.php');
	require_once('class/dto/Image.class.php');
	require_once('class/mysql/ImageMySqlDAO.class.php');
	require_once('class/mysql/ext/ImageMySqlExtDAO.class.php');
	require_once('class/dao/ItemDAO.class.php');
	require_once('class/dto/Item.class.php');
	require_once('class/mysql/ItemMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemMySqlExtDAO.class.php');
	require_once('class/dao/ItemAttributeDAO.class.php');
	require_once('class/dto/ItemAttribute.class.php');
	require_once('class/mysql/ItemAttributeMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemAttributeMySqlExtDAO.class.php');
	require_once('class/dao/ItemAttributeMappingDAO.class.php');
	require_once('class/dto/ItemAttributeMapping.class.php');
	require_once('class/mysql/ItemAttributeMappingMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemAttributeMappingMySqlExtDAO.class.php');
	require_once('class/dao/ItemBrandDAO.class.php');
	require_once('class/dto/ItemBrand.class.php');
	require_once('class/mysql/ItemBrandMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemBrandMySqlExtDAO.class.php');
	require_once('class/dao/ItemBrandMappingDAO.class.php');
	require_once('class/dto/ItemBrandMapping.class.php');
	require_once('class/mysql/ItemBrandMappingMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemBrandMappingMySqlExtDAO.class.php');
	require_once('class/dao/ItemCategoryDAO.class.php');
	require_once('class/dto/ItemCategory.class.php');
	require_once('class/mysql/ItemCategoryMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemCategoryMySqlExtDAO.class.php');
	require_once('class/dao/ItemCategoryMappingDAO.class.php');
	require_once('class/dto/ItemCategoryMapping.class.php');
	require_once('class/mysql/ItemCategoryMappingMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemCategoryMappingMySqlExtDAO.class.php');
	require_once('class/dao/ItemReviewDAO.class.php');
	require_once('class/dto/ItemReview.class.php');
	require_once('class/mysql/ItemReviewMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemReviewMySqlExtDAO.class.php');
	require_once('class/dao/ItemTagDAO.class.php');
	require_once('class/dto/ItemTag.class.php');
	require_once('class/mysql/ItemTagMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemTagMySqlExtDAO.class.php');
	require_once('class/dao/ItemTagMappingDAO.class.php');
	require_once('class/dto/ItemTagMapping.class.php');
	require_once('class/mysql/ItemTagMappingMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemTagMappingMySqlExtDAO.class.php');
	require_once('class/dao/ItemsTempDAO.class.php');
	require_once('class/dto/ItemsTemp.class.php');
	require_once('class/mysql/ItemsTempMySqlDAO.class.php');
	require_once('class/mysql/ext/ItemsTempMySqlExtDAO.class.php');
	require_once('class/dao/LangDAO.class.php');
	require_once('class/dto/Lang.class.php');
	require_once('class/mysql/LangMySqlDAO.class.php');
	require_once('class/mysql/ext/LangMySqlExtDAO.class.php');
	require_once('class/dao/OptionsDAO.class.php');
	require_once('class/dto/Option.class.php');
	require_once('class/mysql/OptionsMySqlDAO.class.php');
	require_once('class/mysql/ext/OptionsMySqlExtDAO.class.php');
	require_once('class/dao/PermissionDAO.class.php');
	require_once('class/dto/Permission.class.php');
	require_once('class/mysql/PermissionMySqlDAO.class.php');
	require_once('class/mysql/ext/PermissionMySqlExtDAO.class.php');
	require_once('class/dao/RolePermissionDAO.class.php');
	require_once('class/dto/RolePermission.class.php');
	require_once('class/mysql/RolePermissionMySqlDAO.class.php');
	require_once('class/mysql/ext/RolePermissionMySqlExtDAO.class.php');
	require_once('class/dao/SaleOrderDAO.class.php');
	require_once('class/dto/SaleOrder.class.php');
	require_once('class/mysql/SaleOrderMySqlDAO.class.php');
	require_once('class/mysql/ext/SaleOrderMySqlExtDAO.class.php');
	require_once('class/dao/SaleOrderItemDAO.class.php');
	require_once('class/dto/SaleOrderItem.class.php');
	require_once('class/mysql/SaleOrderItemMySqlDAO.class.php');
	require_once('class/mysql/ext/SaleOrderItemMySqlExtDAO.class.php');
	require_once('class/dao/SocialMediaDAO.class.php');
	require_once('class/dto/SocialMedia.class.php');
	require_once('class/mysql/SocialMediaMySqlDAO.class.php');
	require_once('class/mysql/ext/SocialMediaMySqlExtDAO.class.php');
	require_once('class/dao/UserDAO.class.php');
	require_once('class/dto/User.class.php');
	require_once('class/mysql/UserMySqlDAO.class.php');
	require_once('class/mysql/ext/UserMySqlExtDAO.class.php');
	require_once('class/dao/UserAddressTbDAO.class.php');
	require_once('class/dto/UserAddressTb.class.php');
	require_once('class/mysql/UserAddressTbMySqlDAO.class.php');
	require_once('class/mysql/ext/UserAddressTbMySqlExtDAO.class.php');
	require_once('class/dao/UserRoleDAO.class.php');
	require_once('class/dto/UserRole.class.php');
	require_once('class/mysql/UserRoleMySqlDAO.class.php');
	require_once('class/mysql/ext/UserRoleMySqlExtDAO.class.php');
	require_once('class/dao/UserTypeDAO.class.php');
	require_once('class/dto/UserType.class.php');
	require_once('class/mysql/UserTypeMySqlDAO.class.php');
	require_once('class/mysql/ext/UserTypeMySqlExtDAO.class.php');
	require_once('class/dao/VehicleTypeDAO.class.php');
	require_once('class/dto/VehicleType.class.php');
	require_once('class/mysql/VehicleTypeMySqlDAO.class.php');
	require_once('class/mysql/ext/VehicleTypeMySqlExtDAO.class.php');
	require_once('class/dao/WarehouseDAO.class.php');
	require_once('class/dto/Warehouse.class.php');
	require_once('class/mysql/WarehouseMySqlDAO.class.php');
	require_once('class/mysql/ext/WarehouseMySqlExtDAO.class.php');
	require_once('class/dao/WishlistDAO.class.php');
	require_once('class/dto/Wishlist.class.php');
	require_once('class/mysql/WishlistMySqlDAO.class.php');
	require_once('class/mysql/ext/WishlistMySqlExtDAO.class.php');

?>