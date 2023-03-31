<?php

/**
 * @file
 * Migrate data from molla front-end project.
 *
 * Products, product variations, attributes,
 * attribute values, categories, brands.
 */

$molla_path = '/home/kent/WebstormProjects/molla';
$molla_json_path = $molla_path . '/molla-react/api/src/data/demo1.json';
$molla_image_path = $molla_path . '/molla-react/api/src/images';

$image_data_path = 'images/molla/product';
$image_target_path = __DIR__ . '/data/' . $image_data_path;
$product_brands_csv_target_path = __DIR__ . '/data/product_brands.csv';
$product_categories_csv_target_path = __DIR__ . '/data/product_categories.csv';
$product_attribute_values_color_csv_target_path = __DIR__ . '/data/product_attribute_values_color.csv';
$product_attribute_values_size_csv_target_path = __DIR__ . '/data/product_attribute_values_size.csv';
$products_csv_target_path = __DIR__ . '/data/products.csv';
$product_variations_csv_target_path = __DIR__ . '/data/product_variations.csv';

$json = json_decode(file_get_contents($molla_json_path), TRUE);
$product_brands_csv = 'id,name,path,parent' . PHP_EOL;
$product_categories_csv = 'id,name,path,parent' . PHP_EOL;
$product_attribute_values_color_csv = 'id,attribute,name,color' . PHP_EOL;
$product_attribute_values_size_csv = 'id,attribute,name' . PHP_EOL;
$products_csv = 'id,stores,title,main_image,detail_images,categories,body' . PHP_EOL;
$product_variations_csv = 'id,product,sku,title,price,attribute_clothes_color,attribute_clothes_size,stock,weight' . PHP_EOL;

$product_brands = $product_categories = [];
$product_attribute_values_color = [];
$product_attribute_values_size = [];

foreach ($json['products'] as $product_index => $product) {

  // Obtain product brands.
  foreach ($product['brands'] as $product_brand) {
    $product_brands[$product_brand['slug']] = [
      'name' => $product_brand['name'],
      'slug' => $product_brand['slug'],
    ];
  }

  // Obtain product categories.
  foreach ($product['category'] as $product_category) {
    $product_categories[$product_category['slug']] = [
      'name' => $product_category['name'],
      'slug' => $product_category['slug'],
    ];
  }

  // Copy all the pictures.
  $images = [];
  $product_index++;
  foreach ($product['pictures'] as $picture_index => $picture) {
    $picture = $picture['url'];
    $picture_index++;
    $source_path = str_replace('/uploads', $molla_image_path, $picture);
    $image_file_name = $product_index . '-' . $picture_index . '.' . get_suffix($picture);
    $target_path = $image_target_path . '/' . $image_file_name;
    copy($source_path, $target_path);
    $images[] = $image_file_name;
  }

  // Make up products.
  $images_with_data_path = array_map(function ($image) use ($image_data_path) {
    return $image_data_path . '/' . $image;
  }, $images);
  $detail_images = implode('|', $images_with_data_path);

  $categories = array_map(function ($category) {
    return $category['name'];
  }, $product['category']);
  $categories_str = '"' . implode(',', $categories) . '"';

  $body = '"' . $product['short_desc'] . '"';
  $products_csv .= "product${product_index},1,${product['name']},${images_with_data_path[0]},${detail_images},${categories_str},${body}" . PHP_EOL;

  // Obtain product attribute values.
  foreach ($product['variants'] as $product_variation) {
    // Obtain attribute values.
    $product_attribute_values_color[$product_variation['color']] = [
      'color' => $product_variation['color'],
      'name' => $product_variation['color_name'],
    ];

    // Obtain size.
    foreach ($product_variation['size'] as $size) {
      $product_attribute_values_size[$size['name']] = $size['name'];
    }
  }
}

foreach (array_values($product_brands) as $index => $brand) {
  $index++;
  $product_brands_csv .= "product_brand${index},${brand['name']},/products/brand/${brand['slug']},\"\"" . PHP_EOL;
}

foreach (array_values($product_categories) as $index => $category) {
  $index++;
  $product_categories_csv .= "product_category${index},${category['name']},/products/category/${category['slug']},\"\"" . PHP_EOL;
}

/**
 * Turn a string into path format.
 *
 * @param string $str
 *   String to be transformed.
 *
 * @return string
 *   String in path format.
 */
function turn_into_path_string(string $str): string {
  return strtolower(str_replace(' ', '-', $str));
}

foreach (array_values($product_attribute_values_color) as $index => $color) {
  $index++;
  $product_attribute_values_color_csv .= "attribute_color${index},clothes_color,${color['name']},${color['color']}" . PHP_EOL;
}

foreach (array_values($product_attribute_values_size) as $index => $size) {
  $index++;
  $product_attribute_values_size_csv .= "attribute_size${index},clothes_size,${size}" . PHP_EOL;
}

// Make product variations for every product through all attributes.
$variation_index = 0;
foreach ($json['products'] as $product_index => $product) {
  foreach (array_values($product_attribute_values_color) as $color_key => $color) {
    $color_key++;
    foreach (array_values($product_attribute_values_size) as $size_key => $size) {
      $variation_index++;
      $size_key++;
      $color_id = turn_into_underscored($color['name']);
      $size_id = turn_into_underscored($size);
      $product_variations_csv .= "product_variation${variation_index},product${product_index},product${product_index}-${color_id}-${size_id},${color['name']} - ${size},100,attribute_color${color_key},attribute_size${size_key},1000,0" . PHP_EOL;
    }
  }
}

file_put_contents($product_brands_csv_target_path, $product_brands_csv);
file_put_contents($product_categories_csv_target_path, $product_categories_csv);
file_put_contents($product_attribute_values_color_csv_target_path, $product_attribute_values_color_csv);
file_put_contents($product_attribute_values_size_csv_target_path, $product_attribute_values_size_csv);
file_put_contents($products_csv_target_path, $products_csv);
file_put_contents($product_variations_csv_target_path, $product_variations_csv);

/**
 * Get the file name suffix.
 */
function get_suffix(string $file_name): string {
  return pathinfo($file_name, PATHINFO_EXTENSION);
}

/**
 * Turn a string into underscored format.
 *
 * @param string $str
 *   String to be transformed.
 *
 * @return string
 *   String in underscored format.
 */
function turn_into_underscored(string $str): string {
  return strtolower(str_replace(' ', '_', $str));
}
