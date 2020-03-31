<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Insights extends Pos {

	# Main PDO Connection Instance
	protected $pdo;
	protected $session;

	public function __construct(){
		global $session, $evelyn;

		$this->evelyn = $evelyn;
		$this->session = $session;
	}

	public function customerGrouping() {

		$datesClass = load_class('dates', 'controllers');

		// RUN SOME FEW ANALYTICS TO GET THE LIST FOR CUSTOMER GROUPING
		$this->customers_list = $this->arrayList(
			"customers", "customer_id", "status=1"
		);

		$this->active_list = $this->arrayList(
			"sales", "customer_id", 
			"(order_date BETWEEN '".$datesClass->dateFormater(array("period"=>"-".ACTIVE_RANGE.""))."' AND '".$datesClass->dateFormater(array("period"=>"+1 days"))."')
			GROUP BY customer_id"
		);

		// customers to be exempted from next query
		$this->undetermined_customers = array_diff(
			$this->customers_list, 
			$this->active_list
		);

		// run the query for the inactive customers
		$this->inactive_list = $this->arrayList(
			"sales", "customer_id", 
			"(order_date BETWEEN '".$datesClass->dateFormater(array("period"=>"-".INACTIVE_RANGE.""))."' AND '".$datesClass->dateFormater(array("period"=>"-".ACTIVE_RANGE.""))."') AND (customer_id IN ('".implode("', '", $this->undetermined_customers)."')) GROUP BY customer_id"
		);

		// now the remaining is the dormant customers
		$this->dormant_list = array_diff(
			$this->undetermined_customers, 
			$this->inactive_list
		);

		// return the results
		return array(
			"active_list"=>$this->active_list,
			"inactive_list"=>$this->inactive_list,
			"dormant_list"=>$this->dormant_list
		);
	}

	public function salesInsight($wk_start, $wk_end) {
		
		// run the query
		$sales_list = $this->evelyn->prepare("
			SELECT 
				MAX(order_amount_paid) AS maximum_amount_paid, 
				MIN(order_amount_paid) AS minimum_amount_paid,
				AVG(order_amount_paid) AS average_sales_amount,
				sales.customer_id, customers.firstname, customers.lastname, customers.customer_id, sales.currency, sales.order_id 
			FROM 
				sales 
			LEFT JOIN customers ON sales.customer_id=customers.customer_id 
			WHERE 
				(sales.order_date >= '$wk_start' AND sales.order_date <= '$wk_end') 
					AND sales.order_status = 'processed' 
			ORDER BY 
				sales.id DESC LIMIT 1
		");

		// execute the statement
		$sales_list->execute();

		// return the results in a function style
		$result = $sales_list->fetch(PDO::FETCH_OBJ);

		return $result;		
	}

	public function topProducts($wk_start, $wk_end) {

		// fetch the top 5 products that has been sold
		$top_products = $this->evelyn->prepare("
			SELECT
				SUM(sd.product_quantity) AS products_quantity, sd.product_id,
				pd.product_title
			FROM
				sales_details sd
			LEFT JOIN sales ON sd.order_id = sales.order_id
			LEFT JOIN products pd ON pd.product_id = sd.product_id
			WHERE
				(DATE(sd.order_date) >= '$wk_start' AND DATE(sd.order_date) <= '$wk_end')
			GROUP BY sd.product_id ORDER BY products_quantity DESC LIMIT 5
		");
		// execute the statement
		$top_products->execute();

		$results = array();

		// return the results in a function style
		while($result = $top_products->fetch(PDO::FETCH_OBJ)) {
			$results[] = $result;
		}

		return $results;	
	}

	public function percentDifference($current_value, $previous_value) {

		$percentage = 0;

		// find the difference between the values
		$difference = ($current_value - $previous_value);
		//print $current_value;

		// find the percentage of the difference
		$percentage = ($difference != 0 && $current_value != 0) ? (($difference / $current_value)) * 100 : 0;

		// ensure that it is not in negative mode
		$percentage = ($percentage < 0) ? ($percentage * -1) : $percentage;

		if($current_value > $previous_value) {
			$prefix = '<span class="text-success"><i class="fa fa-arrow-circle-up"></i> '. $this->toDecimal($difference, 2, ',');
		} else {
			$prefix = '<span class="text-danger"><i class="fa fa-arrow-circle-down"></i>'. $this->toDecimal($difference, 2, ',');
		}

		$percentile = $prefix . ' ('.number_format(round($percentage), 0).'%)</span>';
		
		return $percentile;
	}

    /**
     * A method to convert to decimal place
     * 
     * @param Float $isValue Pass value to convert
     * 
     * @return String
     */
    public function toDecimal($isValue, $decimal = 2, $delimiter = "")
    {
        $this->_total = number_format((float)$isValue, $decimal, '.', $delimiter);

        return $this->_total;
    }

	
	private function _calculate_growth(int $oldValue, int $newValue){
		$growth = (object) ["percentage" => 0, "class" => "muted", "icon" => "minus-circle"];
		# Calculate and return growth percentage and style class
		if(empty($oldValue) && empty($newValue)){
			$growth->percentage = 0;
			$growth->class = "muted";
			$growth->icon = "arrow-circle-right";
		}
		elseif(empty($oldValue) && !empty($newValue)){
			$growth->percentage = intval($newValue)*100;
			$growth->class = "success";
			$growth->icon = "arrow-circle-up";
		}
		elseif(!empty($oldValue) && empty($newValue)){
			$growth->percentage = gmp_neg(intval($oldValue)*100);
			$growth->class = "danger";
			$growth->icon = "arrow-circle-down";
		}
		else{
			$growth->percentage = (($newValue - $oldValue)/$oldValue)*100;
			$growth->class = $oldValue > $newValue ? "danger" : "success";
			$growth->icon = $oldValue > $newValue ? "arrow-circle-down" : "arrow-circle-up";
		}
		return $growth;
	}
	
}