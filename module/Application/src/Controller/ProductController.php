<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use stdClass;
use Album;
use AlbumMySqlExtDAO;
use ConnectionFactory;
use Image;
use ImageMySqlExtDAO;
use Item;
use ItemBrandMappingMySqlExtDAO;
use ItemBrandMySqlExtDAO;
use ItemCategoryMappingMySqlExtDAO;
use ItemCategoryMySqlExtDAO;
use ItemMySqlExtDAO;
use ItemTagMySqlExtDAO;
use User;
use UserMySqlExtDAO;

class ProductController extends AbstractActionController
{
    public static $BEST_DEALS = 1;
    public static $HOT_SELLING_PRODUCTS = 2;
    public static $FEATURED = 3;

    public function indexAction()
    {
        $prefixUrl = MAIN_URL . 'products/';
        $page = 1;
        $limit = 9;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : "";
        $brandId = (isset($_GET['brand']) && $_GET['brand'] != "") ? $_GET['brand'] : "";
        $minPrice = (isset($_GET['min-price']) && $_GET['min-price'] != "") ? $_GET['min-price'] : "";
        $maxPrice = (isset($_GET['max-price']) && $_GET['max-price'] != "") ? $_GET['max-price'] : "";
        $categoriesFiltered = (isset($_GET['c']) && $_GET['c'] != "") ? $_GET['c'] : [];
        $subCategoriesFiltered = (isset($_GET['sc']) && $_GET['sc'] != "") ? $_GET['sc'] : [];
        $brandsFiltered = (isset($_GET['b']) && $_GET['b'] != "") ? $_GET['b'] : [];


        $categoryArray = [];
        $brandsArray = [];

        // Get Brands
        $itemBrandMySqlExtDAO = new ItemBrandMySqlExtDAO();
        $brandsList = $itemBrandMySqlExtDAO->queryAll();

        // Get Categories
        $itemCategoryMySqlExtDAO = new ItemCategoryMySqlExtDAO();
        $categoryList = $itemCategoryMySqlExtDAO->select('parent_id = 0 ORDER BY name ASC');


        foreach ($categoriesFiltered as $row) {
            $subCategories = $itemCategoryMySqlExtDAO->select("parent_id = $row");
            if ($subCategories) {
                foreach ($subCategories as $subRow) {
                    array_push($categoryArray, $subRow->id);
                }
            }
        }

        foreach ($subCategoriesFiltered as $row1) {
            array_push($categoryArray, $row1);
        }

        foreach ($brandsFiltered as $row2) {
            array_push($brandsArray, $row2);
        }

        sort($categoryArray);
        $categoryArray = array_unique($categoryArray);
        $items = self::getItems($categoryArray, $search, $brandsArray, $minPrice, $maxPrice, false, "", $limit, $offset);
        $itemsCount = count(self::getItems($categoryArray, $search, $brandsArray, $minPrice, $maxPrice, false));
        $totalPages = ceil($itemsCount / $limit);

        $isSearchPage = $search != "" ? true : false;

        $banners = ContentController::getBanners(4);


        // get Best Deals
        $bestDeals = self::getItems(false, false, "", "", "", self::$BEST_DEALS, "", 4, 0);
        //print_r($bestDeals);
        $banners = ContentController::getBanners(4);
        $this->layout()->withBanner = true;
        $this->layout()->banners = $banners;
        $data = [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'isSearch' => $isSearchPage,
            'search' => $search,
            'prefixUrl' => $prefixUrl,
            'brandsList' => $brandsList,
            'brandId' => $brandId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'categoryList' => $categoryList,
            'categoriesFiltered' => $categoriesFiltered,
            'subCategoriesFiltered' => $subCategoriesFiltered,
            'brandsFiltered' => $brandsFiltered,
            'bestDeals' => $bestDeals,
        ];
        return new ViewModel($data);
    }
    public function index1Action()
    {
        $itemCategoryMySqlExtDAO = new ItemCategoryMySqlExtDAO();
        $prefixUrl = MAIN_URL . 'products/';
        $categoryList = [];
        $page = 1;
        $limit = 12;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : "";
        $brandId = (isset($_GET['brand']) && $_GET['brand'] != "") ? $_GET['brand'] : "";
        $minPrice = (isset($_GET['min-price']) && $_GET['min-price'] != "") ? $_GET['min-price'] : "";
        $maxPrice = (isset($_GET['max-price']) && $_GET['max-price'] != "") ? $_GET['max-price'] : "";
        $categoriesFiltered = (isset($_GET['categories']) && $_GET['categories'] != "") ? $_GET['categories'] : [];

        $cat1 = $this->params('cat1') ? HelperController::filterInput($this->params('cat1')) : false;
        $cat2 = $this->params('cat2') ? HelperController::filterInput($this->params('cat2')) : false;
        $cat3 = $this->params('cat3') ? HelperController::filterInput($this->params('cat3')) : false;

        // Get Category Slug
        $categorySlug = "";
        $categoryId = false;
        $completeSlug = "";

        if ($cat3) {
            $categorySlug = $cat3;
        } elseif ($cat2) {
            $categorySlug = $cat2;
        } elseif ($cat1) {
            $categorySlug = $cat1;
        }
        if ($cat1) {
            $completeSlug .= "/" . $cat1;
        }
        if ($cat2) {
            $completeSlug .= "/" . $cat2;
        }
        if ($cat3) {
            $completeSlug .= "/" . $cat3;
        }

        if ($categorySlug != "") {
            $categoryInfo = $itemCategoryMySqlExtDAO->queryBySlug($categorySlug);
            if ($categoryInfo) {
                $categoryId = $categoryInfo[0]->id;
            }
        }
        // End of get Category Slug

        $categoryArray = [];
        if ($categoryId) {
            if ($cat3) {
                $cat3Info = $itemCategoryMySqlExtDAO->queryBySlug($cat3);
                array_push($categoryArray, $cat3Info[0]->id);
            } elseif ($cat2 && !$cat3) {
                $cat2Info = $itemCategoryMySqlExtDAO->queryBySlug($cat2);
                $cat2Id = $cat2Info[0]->id;
                $categoriesLevel2 = CategoryController::getCategories("parent_id = $cat2Id");
                foreach ($categoriesLevel2 as $row) {
                    if (count($categoriesFiltered) > 0) {
                        if (in_array($row->id, $categoriesFiltered)) {
                            array_push($categoryArray, $row->id);
                        }
                    } else {
                        array_push($categoryArray, $row->id);
                    }
                }
                $categoryList = $itemCategoryMySqlExtDAO->select("parent_id = $cat2Id ORDER BY name ASC");
                $prefixUrl = MAIN_URL . 'products/' . $cat1 . "/" . $cat2 . "/";
            } elseif ($cat1 && !$cat2 && !$cat3) {
                $cat1Info = $itemCategoryMySqlExtDAO->queryBySlug($cat1);
                $cat1Id = $cat1Info[0]->id;
                $categoriesLevel1 = CategoryController::getCategories("parent_id = $cat1Id");
                foreach ($categoriesLevel1 as $row) {
                    if (count($categoriesFiltered) > 0) {
                        $categoriesFilteredList = implode(',', $categoriesFiltered);
                        $categoriesLevel2 = CategoryController::getCategories("parent_id IN ($categoriesFilteredList)");
                        foreach ($categoriesLevel2 as $row) {
                            array_push($categoryArray, $row->id);
                        }
                    } else {
                        $cat2Info = $itemCategoryMySqlExtDAO->queryBySlug($row->slug);
                        $cat2Id = $cat2Info[0]->id;
                        $categoriesLevel2 = CategoryController::getCategories("parent_id = $cat2Id");
                        foreach ($categoriesLevel2 as $row) {
                            array_push($categoryArray, $row->id);
                        }
                    }
                }
                $categoryList = $itemCategoryMySqlExtDAO->select("parent_id = $cat1Id ORDER BY name ASC");
                $prefixUrl = MAIN_URL . 'products/' . $cat1 . "/";
            }
        } else {
            if (count($categoriesFiltered) > 0) {
                $categoriesFilteredList = implode(',', $categoriesFiltered);
                $categoriesLevel1 = CategoryController::getCategories("parent_id IN ($categoriesFilteredList)");
                foreach ($categoriesLevel1 as $row) {
                    $cat2Info = $itemCategoryMySqlExtDAO->queryBySlug($row->slug);
                    $cat2Id = $cat2Info[0]->id;
                    $categoriesLevel2 = CategoryController::getCategories("parent_id = $cat2Id");
                    foreach ($categoriesLevel2 as $row) {
                        array_push($categoryArray, $row->id);
                    }
                }
            } else {
                $categoryList = $itemCategoryMySqlExtDAO->select('parent_id = 0 ORDER BY name ASC');
            }
        }

        $itemBrandMySqlExtDAO = new ItemBrandMySqlExtDAO();

        if ($cat1) {
            $cat1Info = $itemCategoryMySqlExtDAO->queryBySlug($cat1)[0];
            $brandsList = $itemBrandMySqlExtDAO->getBrandsByCategoryId($cat1Info->id);
        } else {
            $brandsList = $itemBrandMySqlExtDAO->queryAll();
        }

        sort($categoryArray);
        $categoryArray = array_unique($categoryArray);
        $items = self::getItems($categoryArray, $search, $brandId, $minPrice, $maxPrice, false, "", $limit, $offset);
        $itemsCount = count(self::getItems($categoryArray, $search, $brandId, $minPrice, $maxPrice, false));
        $totalPages = ceil($itemsCount / $limit);

        $isSearchPage = $search != "" ? true : false;

        $banners = ContentController::getBanners(4);
        $this->layout()->withBanner = true;
        $this->layout()->banners = $banners;
        print_r($categoryList);
        $data = [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'completeSlug' => $completeSlug,
            'isSearch' => $isSearchPage,
            'search' => $search,
            'cat1' => $cat1,
            'cat2' => $cat2,
            'cat3' => $cat3,
            'categoryList' => $categoryList,
            'prefixUrl' => $prefixUrl,
            'brandsList' => $brandsList,
            'brandId' => $brandId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'categoriesFiltered' => $categoriesFiltered,
        ];
        return new ViewModel($data);
    }
    public function detailsAction()
    {
        $slug = HelperController::filterInput($this->params('slug'));
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $item = $itemMySqlExtDAO->queryBySlug($slug);
        $item = $item[0];

        //Get Categories
        $itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
        $itemCategory = $itemCategoryMappingMySqlExtDAO->getItemCategory($item->id);
        $itemCategoryMySqlExtDAO = new ItemCategoryMySqlExtDAO();
        $parentCategory = $itemCategoryMySqlExtDAO->load($itemCategory->parentId);
        //$mainCategory = $itemCategoryMySqlExtDAO->load($parentCategory->parentId);
        $itemCategoryInfo = $itemCategoryMySqlExtDAO->load($itemCategory->categoryId);
        //Get Brand
        $itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();
        $itemBrand = $itemBrandMappingMySqlExtDAO->getItemBrand($item->id);

        //Album Images
        $imageMySqlExtDAO = new ImageMySqlExtDAO();
        if (USE_FILEMANAGER_UPLOADS) {
            $images = [];
            for ($i = 2; $i < 6; $i++) {
                if (file_exists(BASE_PATH . filemanager_upload_thumb_dir . str_replace('.', '-', $item->sku) . '-' . $i . '-U' . '.jpg')) {
                    //echo 'file exists';
                    $image = HelperController::getImageUrlFromFileManager(str_replace('.', '-', $item->sku) . '-' . $i . '-U' . '.jpg')->image;
                    array_push($images, $image);
                }
            }
            //print_r($images);
        } else {
            $images = $imageMySqlExtDAO->queryByAlbumId($item->albumId);
        }

        // Related Products
        $related = self::getRelatedProducts($itemCategory->categoryId, $item->id);
        $relatedIds = array_map(function ($e) {
            return $e->itemId;
        }, $related);
        $relatedIds = implode(',', $relatedIds);
        $relatedProducts = [];
        if ($relatedIds) {
            $relatedProducts = $itemMySqlExtDAO->select("a.id IN($relatedIds)");
        }

        return new ViewModel([
            'item' => $item,
            'itemCategory' => $itemCategory,
            'parentCategory' => $parentCategory,
            //'mainCategory' => $mainCategory,
            'itemBrand' => $itemBrand,
            'images' => $images,
            'relatedProducts' => $relatedProducts,
            'itemCategoryInfo' => $itemCategoryInfo,
        ]);
    }
    public function bestDealsAction()
    {
        $page = 1;
        $limit = 12;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : false;
        $itemTagMySqlExtDAO = new ItemTagMySqlExtDAO();
        $items = self::getItems(false, false, "", "", "", self::$BEST_DEALS, "", $limit, $offset);
        $itemsCount = count(self::getItems(false, false, "", "", "", self::$BEST_DEALS));
        $totalPages = ceil($itemsCount / $limit);
        $banners = ContentController::getBanners(5);
        $data = [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'spotlight' => false,
            'banner' => $banners[0],
        ];
        return new ViewModel($data);
    }
    public function latestArrivalsAction()
    {
        $langId = HelperController::langId(HelperController::filterInput($this->params('lang')));
        $page = 1;
        $limit = 12;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : false;
        $itemTagMySqlExtDAO = new ItemTagMySqlExtDAO();
        $tagInfo = $itemTagMySqlExtDAO->queryBySlug('latest-arrivals');
        $tagId = $tagInfo[0]->id;
        $items = self::getItems(false, false, $tagId, "", $limit, $offset);
        $itemsCount = count(self::getItems(false, false, $tagId));
        $totalPages = ceil($itemsCount / $limit);

        $ads = ContentController::getContent("type = 'ad1' and lang = $langId ORDER BY display_order asc LIMIT 3");
        $data = [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'ads' => $ads,
        ];
        return new ViewModel($data);
    }

    public function promotionsAction()
    {
        $page = 1;
        $limit = 12;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $catetgories = CategoryController::getCategories('parent_id = 0 ORDER BY display_order ASC, name ASC, id DESC');
        $category = (isset($_GET['category']) && $_GET['category'] != "") ? $_GET['category'] : false;
        $categoryArray = [];
        if ($category) {
            $itemCategoryMySqlExtDAO = new ItemCategoryMySqlExtDAO();
            $cat1Info = $itemCategoryMySqlExtDAO->queryBySlug($category);
            $cat1Id = $cat1Info[0]->id;
            $categoriesLevel1 = CategoryController::getCategories("parent_id = $cat1Id");
            foreach ($categoriesLevel1 as $row) {
                array_push($categoryArray, $row->id);
                $cat2Info = $itemCategoryMySqlExtDAO->queryBySlug($row->slug);
                $cat2Id = $cat2Info[0]->id;
                $categoriesLevel2 = CategoryController::getCategories("parent_id = $cat2Id");
                foreach ($categoriesLevel2 as $row) {
                    array_push($categoryArray, $row->id);
                }
            }
        }
        $itemTagMySqlExtDAO = new ItemTagMySqlExtDAO();
        $tagInfo = $itemTagMySqlExtDAO->queryBySlug('promotions');
        $tagId = $tagInfo[0]->id;
        $items = self::getItems($categoryArray, "", "", "", "", $tagId, "", $limit, $offset);
        $itemsCount = count(self::getItems($categoryArray, "", "", "", "", $tagId));
        $totalPages = ceil($itemsCount / $limit);
        $data = [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'categories' =>  $catetgories,
            'category' => $category,
        ];
        return new ViewModel($data);
    }

    public static function insertItems($items, $fileName)
    {
        $supplierId = $_SESSION['user']->id;
        // Initialize
        $brandIdsNames = [];
        $insertedItems = 0;
        $updatedItems = 0;
        $deletedItems = 0;
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
        $itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();

        // Get Brands
        $brands = BrandController::getBrands();
        foreach ($brands as $brand) {
            $brandIdsNames[$brand->name] = $brand->id;
        }

        // New Sku List
        $newItemsSKUList = array_column($items, 'SKU');

        // Old SKU List
        $oldItems = $itemMySqlExtDAO->queryBySupplierId($supplierId);
        $oldItemsSKUList = array_map(function ($e) {
            return $e->sku;
        }, $oldItems);

        $toBeDeleted = array_diff($oldItemsSKUList, $newItemsSKUList);

        foreach ($items as $row) {
            $albumId = 0;
            $prefix = $supplierId . '-' . HelperController::slugify($row['SKU']);
            $image = HelperController::getOrDownloadImage($row['Image 1'], $prefix);
            $imagesArray = [];
            if ($row['Image 2'] != "") {
                $image1 = HelperController::getOrDownloadImage($row['Image 2'], $prefix);
                if ($image1) {
                    array_push($imagesArray, $image1);
                }
            }
            if ($row['Image 3'] != "") {
                $image2 = HelperController::getOrDownloadImage($row['Image 3'], $prefix);
                if ($image2) {
                    array_push($imagesArray, $image2);
                }
            }
            if ($row['Image 4'] != "") {
                $image3 = HelperController::getOrDownloadImage($row['Image 4'], $prefix);
                if ($image3) {
                    array_push($imagesArray, $image3);
                }
            }

            if ($imagesArray) {
                $albumMySqlExtDAO = new AlbumMySqlExtDAO();
                $albumImageMySqlExtDAO = new ImageMySqlExtDAO();
                $albumObj = new Album();
                $albumObj->displayOrder = 0;
                $albumObj->active = 1;
                $albumId = $albumMySqlExtDAO->insert($albumObj);

                foreach ($imagesArray as $albumImageItem) {
                    $albumImageObj = new Image();
                    $albumImageObj->albumId = $albumId;
                    $albumImageObj->imageName = $albumImageItem;
                    $albumImageMySqlExtDAO->insert($albumImageObj);
                }
            }

            $itemObj = new Item();
            self::populateItem($itemObj, $row, $supplierId, $albumId);
            if ($image) {
                $itemObj->image = $image;
            }

            $itemExists = $itemMySqlExtDAO->queryBySkuAndSupplierId($row['SKU'], $supplierId);
            $date = date('Y-m-d H:i:s');
            $categoryId = ProductController::getCategory($row['Category'], $row['sub category'], $row['product category']);
            if ($itemExists) {
                // delete image
                HelperController::deleteImage($itemExists[0]->image);
                // delete album and images
                if ($itemExists[0]->albumId != 0) {
                    $oldImages = $albumImageMySqlExtDAO->queryByAlbumId($itemExists[0]->albumId);
                    foreach ($oldImages as $oldImage) {
                        HelperController::deleteImage($oldImage->imageName);
                        $albumImageMySqlExtDAO->deleteByAlbumId($itemExists[0]->albumId);
                    }
                    $albumMySqlExtDAO->delete($itemExists[0]->albumId);
                }

                $itemObj->id = $itemExists[0]->id;
                $itemObj->createdAt = $itemExists[0]->createdAt;
                $itemObj->updatedAt = $date;
                //print_r($itemObj);echo '<br>';
                $update = $itemMySqlExtDAO->update($itemObj);
                if ($update) {
                    //echo $itemObj->id.'<br>';
                    if ($categoryId) {
                        CategoryController::updateOrInsertItemCategory($itemObj->id, $categoryId);
                    }
                    if (array_key_exists($row['Brand Name'], $brandIdsNames)) {
                        BrandController::updateOrInsertItemBrand($itemObj->id, $brandIdsNames[$row['Brand Name']]);
                    }
                    $updatedItems++;
                }
            } else {
                //echo 'does not exists<br>';
                $itemObj->updatedAt = $date;
                $itemObj->createdAt = $date;
                $insert = $itemMySqlExtDAO->insert($itemObj);
                if ($insert) {
                    if ($categoryId) {
                        $itemCategoryMappingMySqlExtDAO->insertItemCategory($insert, $categoryId);
                    }
                    if (array_key_exists($row['Brand Name'], $brandIdsNames)) {
                        $itemBrandMappingMySqlExtDAO->insertItemBrand($insert, $brandIdsNames[$row['Brand Name']]);
                    }
                    $insertedItems++;
                }
            }
        }

        // delete Items
        foreach ($toBeDeleted as $del) {
            $delete = self::deleteItemBySku($del);
            if ($delete) {
                $deletedItems++;
            }
        }

        // Update File
        if ($insertedItems != 0 || $updatedItems != 0 || $deletedItems != 0) {
            $userMySqlExtDAO = new UserMySqlExtDAO();
            $userObj = new User();
            $userObj->id = $supplierId;
            $userObj->uploadedFile = $fileName;
            $userMySqlExtDAO->updateFile($supplierId, $fileName);
        }

        $response = new stdClass();
        $response->inserted = $insertedItems;
        $response->updated = $updatedItems;
        $response->deleted = $deletedItems;
        return $response;
    }

    public static function insertBulkItems($items)
    {
        $conn = ConnectionFactory::getConnection();
        //$supplierId = $_SESSION['user']->id;
        $data = [];
        foreach ($items as $row) {
            // $image1 = isset($row['Image 1']) ? mysqli_real_escape_string($conn, strval($row['Image 1'])) : '';
            // $image2 = isset($row['Image 2']) ? mysqli_real_escape_string($conn, strval($row['Image 2'])) : '';
            // $image3 = isset($row['Image 3']) ? mysqli_real_escape_string($conn, strval($row['Image 3'])) : '';
            // $image4 = isset($row['Image 4']) ? mysqli_real_escape_string($conn, strval($row['Image 4'])) : '';
            $sku = isset($row['Ref.']) ? mysqli_real_escape_string($conn, trim(strval($row['Ref.']))) : '';
            $title = isset($row['Product']) ? mysqli_real_escape_string($conn, trim(strval($row['Product']))) : '';
            $description = isset($row['DESCRIPTION']) ?  mysqli_real_escape_string($conn, trim($row['DESCRIPTION'])) : '';
            $length = isset($row['L']) ? mysqli_real_escape_string($conn, trim(strval($row['L']))) : '';
            $width = isset($row['W']) ? mysqli_real_escape_string($conn, trim(strval($row['W']))) : '';
            $height = isset($row['H']) ? mysqli_real_escape_string($conn, trim(strval($row['H']))) : '';
            $weight = isset($row['Weight (Kg)']) ? trim(strval($row['Weight (Kg)'])) : '';
            $brand = isset($row['Brand']) ? trim($row['Brand']) : '';
            $languages = isset($row['Languages']) ? trim($row['Languages']) : '';
            $age = isset($row['AGE']) ? trim(strval($row['AGE'])) : '';
            $qty = isset($row['QTY']) ? trim(strval($row['QTY'])) : '';
            $page = isset($row['Page']) ? trim(strval($row['Page'])) : '';
            $price = isset($row['Selling Price']) ? trim(strval($row['Selling Price'])) : '';
            $specialPrice = isset($row['Special Price']) ? trim(strval($row['Special Price'])) : '';
            $category = isset($row['Category']) ? $row['Category'] : '';
            $subCategory = isset($row['Subcategory']) ? trim($row['Subcategory']) : '';
            $warranty = isset($row['Warranty']) ? trim($row['Warranty']) : '';
            $exchange = isset($row['Exchange']) ? trim($row['Exchange']) : '';
            $processed = 0;
            $specs = "";
            $color = "";
            $size = "";
            $dimensions = "";

            $data[] = "('$title', '$category', '$subCategory', '$sku',
                        '$description', '$specs', '$color', '$size', '$weight', '$dimensions', '$brand',
                        '$qty', '$price', '$specialPrice', '$warranty', '$exchange', '$length', '$width', '$height', 
                        '$languages', '$age', '$page', $processed)";
        }
        $sql  = "INSERT INTO items_temp (`title`, `category`, `product_category`, `sku`,
        `description`, `specs`, `color`, `size`, `weight`, `dimension`, `brand_name`,
        `stock`, `price`, `special_price`, `warranty`, `exchange`, `length`, `width`, `height`, 
        `languages`, `age`, `page`, `processed`) VALUES " . implode(',', $data);

        if (!$conn->query($sql)) {
            $res = false;
            $msg = $conn->error;
            error_log($msg);
            error_log($sql);
        } else {
            $res = true;
            $msg = 'imported';
        }

        $conn->close();
        $cls = new stdClass();
        $cls->res = $res;
        $cls->msg = $msg;
        return $cls;
    }

    public static function processBatch($items, $brandIdsNames = [])
    {
        $supplierId = $_SESSION['user']->id;
        $insertedItems = 0;
        $updatedItems = 0;
        // Initialize
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
        $itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();

        //echo json_encode($items);
        foreach ($items as $row) {
            //print_r($row);
            $update = $insert = false;

            $itemObj = new Item();
            self::populateItem($itemObj, $row, $supplierId);
            $ageRange = ImportController::getAgeRange($row['Age']);
            $itemObj->minAge = $ageRange->minAge;
            $itemObj->maxAge = $ageRange->maxAge;

            $itemExists = $itemMySqlExtDAO->queryBySku($row['SKU']);
            $date = date('Y-m-d H:i:s');
            //$categoryId = ProductController::getCategory1($row['Category'], $row['product category']);
            $categoryIds = ImportController::getCategoryIDs($row['product category']);

            if ($itemExists) {

                $itemObj->id = $itemExists[0]->id;
                $itemObj->createdAt = $itemExists[0]->createdAt;
                $itemObj->updatedAt = $date;
                //print_r($itemObj);echo '<br>';
                $update = $itemMySqlExtDAO->update($itemObj);
                //echo 'update res: ' . json_encode($update);
                if ($update) {
                    $updatedItems++;

                    $itemCategoryMappingMySqlExtDAO->deleteByItemId($itemExists[0]->id);
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $catId) {
                            $itemCategoryMappingMySqlExtDAO->insertItemCategory($itemExists[0]->id, $catId);
                        }
                    }
                    $itemBrandMappingMySqlExtDAO->deleteByItemId($itemExists[0]->id);
                    if (array_key_exists($row['Brand Name'], $brandIdsNames)) {
                        BrandController::updateOrInsertItemBrand($itemObj->id, $brandIdsNames[$row['Brand Name']]);
                    }
                }
            } else {
                //echo 'does not exists<br>';
                $itemObj->updatedAt = $date;
                $itemObj->createdAt = $date;
                $insert = $itemMySqlExtDAO->insert($itemObj);
                //echo 'insert res: ' . json_encode($insert);
                if ($insert) {
                    $insertedItems++;
                    if (!empty($categoryIds)) {
                        foreach ($categoryIds as $catId) {
                            $itemCategoryMappingMySqlExtDAO->insertItemCategory($insert, $catId);
                        }
                    }
                    if (array_key_exists($row['Brand Name'], $brandIdsNames)) {
                        $itemBrandMappingMySqlExtDAO->insertItemBrand($insert, $brandIdsNames[$row['Brand Name']]);
                    }
                }
            }
            if ($update || $insert) {
                //echo 'update or insert<br>';
                $conn =  ConnectionFactory::getConnection();
                //$sql = "UPDATE items_temp set processed = 1 where supplier_id = $supplierId AND sku = '" . $row['SKU'] . "'";
                $sql = "UPDATE items_temp set processed = 1 where sku = '" . $row['SKU'] . "'";
                if (!$conn->query($sql)) {
                    $msg = $conn->error;
                    //echo $msg;
                }
            }
        }

        $response = new stdClass();
        $response->inserted = $insertedItems;
        $response->updated = $updatedItems;
        return $response;
    }

    public static function populateItem(&$itemObj, $row, $supplierId)
    {
        $itemObj->title = $row['Title'];
        $itemObj->description = (isset($row['Description']) && !empty($row['Description'])) ? $row['Description'] : "";
        $itemObj->regularPrice = (isset($row['Price']) && !empty($row['Price'])) ? $row['Price'] : 0;
        $itemObj->salePrice = (isset($row['Special Price']) && !empty($row['Special Price'])) ? $row['Special Price'] : 0;
        $itemObj->weight = (isset($row['Weight']) && !empty($row['Weight'])) ? $row['Weight'] : "";
        $itemObj->length = (isset($row['Length']) && !empty($row['Length'])) ? $row['Length'] : "";
        $itemObj->width = (isset($row['Width']) && !empty($row['Width'])) ? $row['Width'] : "";
        $itemObj->height = (isset($row['Height']) && !empty($row['Height'])) ? $row['Height'] : "";
        $itemObj->height = (isset($row['Height']) && !empty($row['Height'])) ? $row['Height'] : "";
        $itemObj->sku = $row['SKU'];
        $itemObj->qty = (isset($row['Stock']) && !empty($row['Stock'])) ? $row['Stock'] : 0;
        $itemObj->specification = (isset($row['Specification']) && !empty($row['Specification'])) ? $row['Specification'] : "";
        $itemObj->color = (isset($row['Color']) && !empty($row['Color'])) ? $row['Color'] : "";
        $itemObj->size = (isset($row['Size']) && !empty($row['Size'])) ? $row['Size'] : "";
        $itemObj->dimensions = (isset($row['Dimensions']) && !empty($row['Dimensions'])) ? $row['Dimensions'] : "";
        $itemObj->supplierId = $supplierId;
        $itemObj->displayOrder = 0;
        $itemObj->slug = self::slugify($row['Title'], $row['SKU']);
        $itemObj->warranty = (isset($row['Warranty']) && !empty($row['Warranty'])) ? $row['Warranty'] : "";
        $itemObj->exchange = (isset($row['Exchange']) && !empty($row['Exchange'])) ? $row['Exchange'] : "";
    }

    public static function deleteItemBySku($sku)
    {
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
        $itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();
        $item = $itemMySqlExtDAO->queryBySku($sku);
        $itemId = $item[0]->id;
        $itemCategoryMappingMySqlExtDAO->deleteByItemId($itemId);
        $itemBrandMappingMySqlExtDAO->deleteByItemId($itemId);
        $delete = $itemMySqlExtDAO->delete($itemId);
        return $delete;
    }

    public static function getFinalPrice($regularPrice, $salePrice, $raw = false)
    {
        if ($salePrice != 0) {
            if (!$raw) {
                return number_format(floatval($salePrice));
            }
            return floatval($salePrice);
        } elseif ($regularPrice != 0) {
            if (!$raw) {
                return number_format(floatval($regularPrice));
            }
            return floatval($regularPrice);
        } else {
            return 'n/a';
        }
    }

    public static function getProductImage($imageName)
    {
        if ($imageName != "" && $imageName != null) {
            if (file_exists(BASE_PATH . upload_image_dir . $imageName)) {
                return BASE_URL . upload_image_dir . $imageName;
            }
        }
        return PRODUCT_PLACEHOLDER_IMAGE_URL;
    }

    public static function getItems($categoryId = false, $search = "", $brandId = "", $minPrice = "", $maxPrice = "", $tagId = false, $orderBy = "", $limit = 0, $offset = 0)
    {
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $items = $itemMySqlExtDAO->getItems($categoryId, $search, $brandId, $minPrice, $maxPrice, $tagId, $orderBy, $limit, $offset);
        return $items;
    }

    public static function getRelatedProducts($categoryId, $excludeId)
    {
        $itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
        $list = $itemCategoryMappingMySqlExtDAO->getListOfItemsInCategory($categoryId, $excludeId);
        return $list;
    }

    public static function getCategoryBanner($cat1 = false, $cat2 = false, $cat3, $cat1Info = false, $cat2info = false, $cat3Info = false)
    {
        $bannerImage = PRODUCT_BANNER_PLACEHOLDER_URL;
        if ($cat1) {
            if (@getimagesize(BASE_PATH . upload_image_dir . $cat1Info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat1Info[0]->bannerImage);
            }
        }
        if ($cat2) {
            if (@getimagesize(BASE_PATH . upload_image_dir . $cat2info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat2info[0]->bannerImage);
            } elseif (@getimagesize(BASE_PATH . upload_image_dir . $cat1Info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat1Info[0]->bannerImage);
            }
        }
        if ($cat3) {
            if (@getimagesize(BASE_PATH . upload_image_dir . $cat3Info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat3Info[0]->bannerImage);
            } elseif (@getimagesize(BASE_PATH . upload_image_dir . $cat2info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat2info[0]->bannerImage);
            } elseif (@getimagesize(BASE_PATH . upload_image_dir . $cat1Info[0]->bannerImage)) {
                $bannerImage = HelperController::getImageUrl($cat1Info[0]->bannerImage);
            }
        }
        //echo 'banner-image1-<br>'.$bannerImage .'<br>-banner';
        return $bannerImage;
    }

    public static function getCategory($cat1 = "", $cat2 = "", $cat3 = "")
    {
        $conn = ConnectionFactory::getConnection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT
            c.`id`
            FROM
            item_category a
            LEFT OUTER JOIN item_category b
            ON a.`id` = b.`parent_id`
            LEFT OUTER JOIN item_category c
            ON b.`id` = c.`parent_id`
            WHERE c.`name` IS NOT NULL
            AND c.`name` = '$cat3' AND b.`name` = '$cat2' AND a.`name` = '$cat1'
            ORDER BY c.`name` ASC LIMIT 1 OFFSET 0";
        $rows = $conn->query($sql);
        $conn->close();
        if ($rows->num_rows > 0) {
            // output data of each row
            $row  =  $rows->fetch_object();
            return $row->id;
        } else {
            return 0;
        }
    }

    public static function getCategory1($cat1 = "", $cat2 = "")
    {
        $conn = ConnectionFactory::getConnection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT
            b.`id`
            FROM
            item_category a
            LEFT OUTER JOIN item_category b
            ON a.`id` = b.`parent_id`
            WHERE b.`name` IS NOT NULL
            AND b.`name` = '$cat2' AND a.`name` = '$cat1'
            ORDER BY b.`name` ASC LIMIT 1 OFFSET 0";
        $rows = $conn->query($sql);
        $conn->close();
        if ($rows->num_rows > 0) {
            // output data of each row
            $row  =  $rows->fetch_object();
            return $row->id;
        } else {
            return 0;
        }
    }

    public static function slugify($title, $sku = "")
    {
        $itemMySqlExtDAO = new ItemMySqlExtDAO();
        $slug = HelperController::slugify($title);
        $item = $itemMySqlExtDAO->queryBySlug($slug);
        if ($item) {
            $item = $item[0];
            if ($item->sku == $sku && $item->supplierId == $_SESSION['user']->id && $item->slug == $slug) {
                return $item->slug;
            } else {
                $c = 1;
                while ($itemMySqlExtDAO->queryBySlug($slug)) {
                    $slug =  HelperController::slugify($title);
                    $slug = $slug . '-' . $c;
                    $c++;
                }
                return $slug;
            }
        } else {
            $c = 1;
            while ($itemMySqlExtDAO->queryBySlug($slug)) {
                $slug =  HelperController::slugify($title);
                $slug = $slug . '-' . $c;
                $c++;
            }
            return $slug;
        }
    }

    public static function insertItemsIntoTempTable($targetFile, $supplierId)
    {
        $conn = ConnectionFactory::getConnection();

        $sql  = "LOAD DATA LOCAL INFILE '$targetFile' INTO TABLE `items_temp` CHARACTER SET 'utf8' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES SET supplier_id = $supplierId, processed=0, id=NULL";
        if (!$conn->query($sql)) {
            echo ("Error description: " . $conn->error);
            $res = false;
            $msg = $conn->error;
        } else {
            $res = true;
            $msg = 'imported';
        }

        $conn->close();
        $cls = new stdClass();
        $cls->res = $res;
        $cls->msg = $msg;
        return $cls;
    }

    public function shopAction()
    {
        $prefixUrl = MAIN_URL . 'shop/';
        $page = 1;
        $limit = 9;
        $offset = 0;
        if (isset($_GET['page']) && $_GET['page'] != "") {
            $page = $_GET['page'];
            $offset = ($page - 1) * $limit;
        }
        $search = (isset($_GET['search']) && $_GET['search'] != "") ? $_GET['search'] : "";
        $brandId = (isset($_GET['brand']) && $_GET['brand'] != "") ? $_GET['brand'] : "";
        $minPrice = (isset($_GET['min-price']) && $_GET['min-price'] != "") ? $_GET['min-price'] : "";
        $maxPrice = (isset($_GET['max-price']) && $_GET['max-price'] != "") ? $_GET['max-price'] : "";
        $categoriesFiltered = (isset($_GET['c']) && $_GET['c'] != "") ? $_GET['c'] : [];
        $subCategoriesFiltered = (isset($_GET['sc']) && $_GET['sc'] != "") ? $_GET['sc'] : [];
        $brandsFiltered = (isset($_GET['b']) && $_GET['b'] != "") ? $_GET['b'] : [];
        // print_r($brandsFiltered);
        // echo '<br><br><br>';
        $cat1 = $this->params('cat1') ? HelperController::filterInput($this->params('cat1')) : false;
        $cat2 = $this->params('cat2') ? HelperController::filterInput($this->params('cat2')) : false;
        $categoryArray = [];
        $brandsArray = [];

        // Get Brands
        $itemBrandMySqlExtDAO = new ItemBrandMySqlExtDAO();
        $brandsList = $itemBrandMySqlExtDAO->queryAll();

        // Get Categories
        $itemCategoryMySqlExtDAO = new ItemCategoryMySqlExtDAO();
        $categoryList = $itemCategoryMySqlExtDAO->select('parent_id = 0 ORDER BY name ASC');


        $featuredCategories = [];
        if ($cat2) {
            $subCategory = $itemCategoryMySqlExtDAO->queryBySlug($cat2);
            array_push($categoryArray, $subCategory[0]->id);
            $prefixUrl .= $cat1 . '/' . $cat2;
        } elseif ($cat1) {
            $category = $itemCategoryMySqlExtDAO->queryBySlug($cat1);
            $categoryId = $category[0]->id;
            $subCategories = $itemCategoryMySqlExtDAO->select("parent_id = $categoryId");
            if ($subCategories) {
                foreach ($subCategories as $subRow) {
                    array_push($categoryArray, $subRow->id);
                }
            }
            $prefixUrl .= $cat1;
            $featuredCategories = $subCategories;
        } else {
            $featuredCategories = CategoryController::getCategories();
        }

        if ($cat2) {
            $items = self::getItems($categoryArray, $search, $brandsFiltered, $minPrice, $maxPrice, false, "", $limit, $offset);
            $itemsCount = count(self::getItems($categoryArray, $search, $brandsFiltered, $minPrice, $maxPrice, false));
            $totalPages = ceil($itemsCount / $limit);
        }

        // get Best Deals
        $bestDeals = self::getItems(false, false, "", "", "", self::$BEST_DEALS, "", 4, 0);
        $hotSelling = self::getItems(false, false, "", "", "", self::$HOT_SELLING_PRODUCTS, "", 4, 0);
        //$banners = ContentController::getBanners(4);
        //$this->layout()->withBanner = true;
        //$this->layout()->banners = $banners;
        $this->layout()->htmlClass = 'header-style-2';
        $this->layout()->header2 = true;
        $data = [
            'cat1' => $cat1,
            'cat2' => $cat2,
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            //'isSearch' => $isSearchPage,
            'search' => $search,
            'prefixUrl' => $prefixUrl,
            'brandsList' => $brandsList,
            'brandId' => $brandId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'categoryList' => $categoryList,
            'categoriesFiltered' => $categoriesFiltered,
            'subCategoriesFiltered' => $subCategoriesFiltered,
            'brandsFiltered' => $brandsFiltered,
            'bestDeals' => $bestDeals,
            'hotSelling' => $hotSelling,
            'featuredCategories' => $featuredCategories,
            'subCategories' => $subCategories,
        ];
        return new ViewModel($data);
    }
}
