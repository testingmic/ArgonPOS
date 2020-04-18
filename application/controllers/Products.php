<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Products extends Pos {
	const PRODUCTID_PREFIX = "PDT";

	protected $imagesUrl;
	protected $defaultImg;


	# Main PDO Connection Instance
	protected $session;

	public function __construct($clientId = null){
		global $session, $config, $pos;
		
		parent::__construct();

		$this->imagesUrl = $config->base_url();
		$this->defaultImg = "default.png";
		$this->clientId = (!empty($clientId)) ? $clientId : $this->clientId;
	}

    /**
     * A method to fetch all branches details from DB
     *
     * @param String $branchID Pass branch id to fetch details
     *
     * @return String $this->_message
     */
    public function getAllBranches($branchID = false)
    {
        $this->_message = false;

        $condition = ($branchID == false) ? false : "AND id = '{$branchID}'";

        $stmt = $this->db->prepare(
            "SELECT * FROM branches WHERE clientId='{$this->clientId}' AND deleted='0' {$condition}"
        );

        if ($stmt->execute()) {
            $this->_message = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        return $this->_message;
    }

    /**
     * A method to count data and return results
     *
     * @param String $branchID Pass branch id to fetch details
     *
     * @return String $this->_message
     */
    public function getCountdata($tableName, $whereClause)
    {
        $this->_message = 0;

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM {$tableName} WHERE {$whereClause}"
        );

        if ($stmt->execute()) {
            $this->_message = $stmt->fetch(PDO::FETCH_OBJ)->total;
        }

        return $this->_message;
    }

	public function all($returnCount = false, $branchId = null) {

		$condition = (empty($branchId)) ? null : " &&  p.branchId = '{$branchId}'";

		$stmt = $this->db->query("
			SELECT *, p.id AS pid, 
				IF(source = 'Vend', product_image, 
				CONCAT('', '', IFNULL(product_image, '$this->defaultImg'))) AS image, 
				product_image 
			FROM products p
			LEFT JOIN products_categories pc ON p.category_id = pc.category_id 
			WHERE p.clientId = '{$this->clientId}' {$condition}
		");
		
		if($returnCount) return $stmt->rowCount();

		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function allWarehouseProducts() {

		$sql = "SELECT *, p.id AS pid, 
			IF(source = 'Vend', product_image, CONCAT('$this->imagesUrl', '', IFNULL(product_image, '$this->defaultImg'))) AS image 
		FROM warehouse p
		LEFT JOIN products_categories pc ON p.category_id = pc.category_id";

		$stmt = $this->db->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function allto($returnCount = false){

		$sql = "SELECT *, CONCAT('$this->imagesUrl', '/', IFNULL(product_image, '$this->defaultImg')) AS image FROM products p
		LEFT JOIN products_categories pc ON p.category_id = pc.category_id";
		$stmt = $this->db->query($sql);
		if($returnCount) return $stmt->rowCount();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Fetch and return product categories
	 * @return [object]
	 */
	public function getCategories(){
		$stmt = $this->db->query("SELECT * FROM products_categories WHERE clientId = '{$this->clientId}'");
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Fetch and return product types
	 * @return [object]
	 * @deprecated
	 */
	public function getTypes(){
		$stmt = $this->db->query("SELECT * FROM products_types");
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function getProduct(string $productId, $product = "", $branchId = "", $column = "product_id"){

		if(empty($productId)) return false;
		$productId = xss_clean($productId);

		$condition = (!empty($branchId)) ? " && branchId = '".xss_clean($branchId)."'" : null;

		$stmt = $this->db->prepare("
			SELECT *, id AS pid,
				IF(source = 'Vend', product_image, 
				CONCAT('', '', IFNULL(product_image, '$this->defaultImg'))) AS image 
			FROM products WHERE status='1' AND $column = '{$productId}' {$condition} LIMIT 1
		");
		$stmt->execute([$productId]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		
		if(!empty($product)) {
			$product = $result;
		}
		else return $result;
	}

	public function addProduct(stdClass $product){

		$params = [
			$product->productId,
			'db', $product->branchId, 
			$product->clientId,
			$product->category,
			$product->title,
			$product->description,
			$product->price,
			$product->cost,
			$product->image,
			$product->userId,
			(isset($product->threshold)) ? $product->threshold : null,
			$product->quantity,
			$product->expiry_date
		];
		$sql = "
		INSERT INTO products (
			product_id, 
			source, branchId, clientId,
			category_id, 
			product_title, product_description, 
			product_price, cost_price, product_image, added_by, threshold,
			quantity, expiry_date
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$this->db->prepare($sql)->execute($params);

		$productId = $this->lastRowId("products");

		// Record user activity
		$this->userLogs('products', $product->productId, 'Added a new product into the database.');

		// record the stock quantity of the products
		$stockInput = $this->db->prepare("
			INSERT INTO products_stocks 
			SET clientId = '{$product->clientId}', 
				branchId='{$product->branchId}', 
				auto_id='{$product->autoId}', product_id='{$productId}', 
				cost_price='{$product->cost}', retail_price='{$product->price}', 
				quantity='{$product->quantity}', total_quantity='{$product->quantity}',
				threshold='{$product->threshold}', recorded_by='{$product->userId}'
		");
		return $stockInput->execute();
	}

	public function updateProduct(stdClass $product){
		$params = [
			$product->expiry_date,
			$product->category,
			$product->title,
			$product->description,
			$product->price,
			$product->cost,
			$product->threshold
			
		];

		if(!empty($product->image)) array_push($params, $product->image);
		
		$sql = "UPDATE products SET
			expiry_date = ?,
			category_id = ?, product_title = ?, 
			product_description = ?, 
			product_price = ?, cost_price = ?, 
			threshold = ? ".(empty($product->image) ? '' : ', product_image = ?')." 
			WHERE (id = '{$product->productId}' OR product_id = '{$product->productId}') AND clientId = '{$product->clientId}' LIMIT 1";

		// Record user activity
		$this->userLogs('products', $product->productId, 'Updated the details of the product in the database.');
		
		return $this->db->prepare($sql)->execute($params);
	}

	private function generate_product_id(){
		return self::PRODUCTID_PREFIX.random_string("nozero", 8);
	}


	function getDefaultImage($returnFullPath = true){
		return $returnFullPath ? $this->imagesUrl."/".$this->defaultImg : $this->defaultImg;
	}

	function getProductImage($productId){
		$sql = "SELECT product_image FROM products WHERE product_id = ? LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$productId]);
		$res = $stmt->fetch(PDO::FETCH_OBJ);
		return empty($res->product_image) ? $this->defaultImg : $res->image;
	}

	function removeProduct($productId, $branchId){

		// Record user activity
		$this->userLogs('products', $productId, 'Deleted the product from the database.');

		// update the product details
		return $this->db->prepare("
			UPDATE products SET status='0' 
			WHERE product_id = ? AND branchId = ? LIMIT 1
		")->execute([$productId, $branchId]);
	}

	public function addStockToBranch(stdClass $product){

		$params = [
			$product->expiry_date,
			$product->product_id,
			$product->category_id,
			$product->product_title,
			$product->product_description,
			$product->product_price,
			$product->cost_price,
			$product->image,
			$product->threshold,
			$product->transferQuantity,
			$product->branchId,
			$this->clientId,
			isset($product->userId) ? $product->userId : null
		];
		$sql = "INSERT INTO products (
			expiry_date, product_id, category_id, 
			product_title, product_description, 
			product_price, cost_price, product_image, threshold, quantity,
			branchId, clientId, added_by
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$this->db->prepare($sql)->execute($params);

		// get the product id
		$productId = $this->lastRowId("products");

		// record the stock quantity of the products
		$stockInput = $this->db->prepare("
			INSERT INTO products_stocks 
			SET clientId = '{$this->clientId}', 
				branchId='{$product->branchId}', 
				auto_id='{$product->stockAutoId}', 
				product_id='{$productId}', 
				cost_price='{$product->cost_price}', 
				retail_price='{$product->product_price}', 
				quantity='{$product->transferQuantity}', 
				total_quantity='{$product->transferQuantity}',
				threshold='{$product->threshold}', recorded_by='{$this->session->userId}'
		");
		return $stockInput->execute();
	}

	public function addToInventory(stdClass $product){
		$params = [
			$product->transferProductID,
			$product->branchId,
			$product->transferProductQuantity,
			$this->clientId,
			$product->selling_price,
			(isset($product->userId)) ? $product->userId : null
		];
		$sql = "INSERT INTO inventory (product_id, branchId, quantity, clientId, selling_price, recorded_by) VALUES (?, ?, ?, ?, ?, ?)";

		return $this->db->prepare($sql)->execute($params);
	}

	public function updateBranchProductStock(stdClass $product){
		
		$sql = "UPDATE products SET quantity = (quantity + $product->transferProductQuantity) WHERE product_id = '{$product->transferProductID}' && branchId = '{$product->branchId}'";

		return $this->db->prepare($sql)->execute();
	}


	public function updateWareHouseStock($insertQuery, $updateQuery) {

		$this->db->beginTransaction();

		try {
			
			$this->db->exec($insertQuery);
			$this->db->exec($updateQuery);

			$this->db->commit();

			return true;

		} catch(PDOException $e) {
			$this->db->rollBack();
			return false;
		}
	}

}