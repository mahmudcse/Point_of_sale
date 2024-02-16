<?php
if (! defined ( 'BASEPATH' ))
	
	exit ( 'No direct script access allowed' );
	
	/*
 *
 *
 *
 * @author : Sharif Uddin
 *
 *
 *
 * date : April 01, 2016
 *
 *
 *
 */
class Ledger extends MY_Controller 

{
	
	/**
	 * *default functin, redirects to login page if no admin logged in yet**
	 */
	public function index() 

	{
		
		// commonTasks();
		redirect ( base_url () . 'index.php/ledger/search', 'refresh' );
	}
	public function commonTasks() {
		$data = parent::commonTasks ();
		
		$data ['component'] = 'Ledger';
		
		return $data;
	}
	public function showledger() 

	{
		$data = $this->searchLedger ();
		
		$this->load->view ( 'ledger/search/index.php', $data );
	}
	public function ledgerpdf() 

	{
		$data = $this->searchLedger ();
		
		$this->load->view ( 'ledger/search/content', $data );
		
		// Get output html
		
		$html = $this->output->get_output ();
		
		// Load library
		
		$this->load->library ( 'dompdf_gen' );
		
		$this->dompdf->load_html ( $html );
		
		$this->dompdf->render ();
		
		$this->dompdf->stream ( "Ledger.pdf" );
	}
	public function searchLedger() 

	{
		$data = $this->commonTasks ();
		
		$data ['search'] = '';
		
		
		$data ['accountId'] = Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
		
		if ($this->input->get ( 'accountId' ) != null)
		
			$data ['accountId'] = $this->input->get ( 'accountId' );
		
		if ($this->input->post ( 'accountId' ) != null)
			
			$data ['accountId'] = $this->input->post ( 'accountId' );
		
		$data ['userId'] = 1;
		
		if ($this->input->get ( 'userId' ) != null)
				
			$data ['userId'] = $this->input->get ( 'userId' );
		
		if ($this->input->post ( 'userId' ) != null)
			
			$data ['userId'] = $this->input->post ( 'userId' );
		
		$data ['stdate'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		$data ['page_title'] = 'Dashboard';
		
		$data ['page_name'] = 'home';
		
		$data ['searchAction'] = 'ledger/showledger';
		
		$data ['downloadAction'] = 'ledger/ledgerpdf';
		
		$data ['account'] = array ();
		
		foreach ( $this->load ( 'account' ) as $row ) {
			
			$data ['account'] [$row->componentId] = $row->uniqueCode;
		}
		
		$data ['customer'] = array (
				- 1 => 'Select user' 
		);
		
		foreach ( $this->load ( 'customer' ) as $row ) {
			
			$data ['customer'] [$row->componentId] = $row->name;
		}
		
		$filter = " AND accountId = " . $data ['accountId'] . " ";
		
		if ($data ['userId'] != null)
			
			$filter .= " AND userId = " . $data ['userId'] . " ";
		
		$transSQL = "SELECT t.componentId, DATE_FORMAT(t.tdate, '%d-%m-%y') as tdate, t.description, t.uniqueCode,

						ROUND(CASE WHEN d.type =1 THEN d.quantity* d.unitPrice ELSE NULL END,2) as dr, 

	  					ROUND(CASE WHEN d.type =-1 THEN d.quantity* d.unitPrice ELSE NULL END,2) as cr, 

						d.`type`*d.quantity*d.unitPrice amt

					FROM transaction_detail d

					INNER JOIN transaction t ON (d.transactionId = t.componentId)

					WHERE (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' ) $filter 

					ORDER BY tdate DESC, t.componentId DESC";
		
		// echo $transSQL;
		
		$cbSQL = "SELECT ROUND(SUM(d.quantity*d.unitPrice*d.type),2) closingBalance

					FROM transaction_detail d

					INNER JOIN transaction t ON (d.transactionId = t.componentId)

					WHERE t.tdate <= '" . $data ['etdate'] . "' $filter";
		
		$obSQL = "SELECT ROUND(SUM(d.quantity*d.unitPrice*d.type),2) openineBalance

					FROM transaction_detail d

					INNER JOIN transaction t ON (d.transactionId = t.componentId)

					WHERE t.tdate < '" . $data ['stdate'] . "' $filter";
		
		$query = $this->db->query ( $transSQL );
		
		$data ['searchData'] = $query->result ();
		
		$queryOb = $this->db->query ( $obSQL );
		
		$data ['ob'] = 0.0;
		
		$data ['cb'] = 0.0;
		
		foreach ( $queryOb->result () as $row ) {
			
			$data ['ob'] = $row->openineBalance;
		}
		
		$queryCb = $this->db->query ( $cbSQL );
		
		foreach ( $queryCb->result () as $row ) {
			
			$data ['cb'] = $row->closingBalance;
		}
		
		$balance = $data ['cb'];
		
		foreach ( $data ['searchData'] as $row ) {
			
			$amt = $row->amt;
			
			$row->amt = $balance;
			
			$balance -= $amt;
		}
		
		$data ['propertyArr'] = [ 
				'tdate' => 'Date',
				'description' => 'Particulars',
				'uniqueCode' => 'Reference',
				'dr' => 'Dr',
				'cr' => 'Cr',
				'amt' => 'Balance' 
		];
		
		$data ['addmodifyAction'] = 'index.php/transaction/add';
		
		// Capitalize the first letter
		
		return $data;
	}
	public function recpay() 

	{
		$data = $this->commonTasks();
		
		$data ['custgroup'] = Applicationconst::OPTION_ALL;
		
		$data ['stdate'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		if ($this->input->post ( 'custgroup' ) != null)
					
				$data ['custgroup'] = $this->input->post ( 'custgroup' );
		
		$data ['custgroups'] = [   Applicationconst::OPTION_ALL=>Applicationconst::OPTION_ALL,
								   Applicationconst::CUSTOMER_GROUP_GUDHOLI=>Applicationconst::CUSTOMER_GROUP_GUDHOLI,
								   Applicationconst::CUSTOMER_GROUP_PADDMA=>Applicationconst::CUSTOMER_GROUP_PADDMA];;
		
		$data ['page_title'] = 'Receives & Payments';
		
		$data ['page_name'] = 'home';
		
		$data ['searchAction'] = 'ledger/recpay';
		
		$customerCond = ($data ['custgroup'] != Applicationconst::OPTION_ALL)? " AND c.customergroup = '" . $data ['custgroup'] . "' " : "";
		
		$recSQL =" SELECT c.componentId, c.name,
					ROUND(SUM( CASE WHEN t.tdate < '" . $data ['stdate'] . "' THEN d.quantity* d.unitPrice*d.type ELSE 0 END),2) AS ob,
					ROUND(SUM(CASE WHEN d.type =1 AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as dr, 
					ROUND(SUM(CASE WHEN d.type =-1 AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as cr, 	
					ROUND(SUM( CASE WHEN t.tdate <= '" . $data ['etdate'] . "' THEN d.`type`*d.quantity*d.unitPrice ELSE 0 END),2) amt
				FROM transaction_detail d
				INNER JOIN transaction t ON (d.transactionId = t.componentId)
				INNER JOIN customer c ON (c.componentId = d.userId)
				WHERE d.accountId = " . Applicationconst::ACCOUNT_HEAD_RECEIVABLE ." ". $customerCond ."
				GROUP BY c.componentId  
				ORDER BY c.name ASC";


		
	
		$paySQL = "SELECT c.componentId, c.name,
						ROUND(SUM( CASE WHEN t.tdate < '" . $data ['stdate'] . "' THEN -1*d.quantity* d.unitPrice*d.type ELSE 0 END),2) AS ob,
						ROUND(SUM(CASE WHEN d.type =1 AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as dr,
						ROUND(SUM(CASE WHEN d.type =-1 AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as cr,
						ROUND(SUM( CASE WHEN t.tdate <= '" . $data ['etdate'] . "' THEN -1*d.`type`*d.quantity*d.unitPrice ELSE 0 END),2) amt
					FROM transaction_detail d
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
					INNER JOIN customer c ON (c.componentId = d.userId)
					WHERE d.accountId = " . Applicationconst::ACCOUNT_HEAD_PAYABLE ." ". $customerCond ."
					GROUP BY c.componentId 
					ORDER BY amt DESC ";
		
		// echo $recSQL;
		
		$recquery = $this->db->query ( $recSQL );
		
		$data ['recData'] = $recquery->result ();
		
		$payquery = $this->db->query ( $paySQL );
		
		$data ['payData'] = $payquery->result ();
		
		$data ['propertyArr'] = [ 
				'name' => 'Party',
				'ob' => 'Opening Balance',
				'dr' => 'Dr',
				'cr' => 'Cr',
				'amt' => 'Balance' 
		];
		
		$data ['ledgerUrl'] = 'ledger/search';
		
		$this->load->view ( 'ledger/recpay/index.php', $data );
	}
	public function profitloss() 

	{
		$data = $this->commonTasks ();
		
		$data ['search'] = '';
		
		$data ['stdate'] = date ( 'Y-m-d', strtotime ( "-1 month" ) );
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		$data ['page_title'] = 'Profit & Loss';
		
		$data ['page_name'] = 'home';
		
		$data ['searchAction'] = 'ledger/profitloss';
		
		$salesSummerySQL= "SELECT SUM(sales) sales, SUM(commission) commission, SUM(opexp) opexp,  SUM(otherexp) otherexp,  SUM(cogs) cogs
				FROM(
				SELECT itemId, i.itemName , ROUND(SUM(CASE WHEN accountId = 13 THEN
				quantity*unitPrice ELSE 0 END),2) AS sales , 
				 ROUND(SUM(CASE WHEN accountId = 8 THEN quantity*unitPrice ELSE 0 END),2) AS commission, 
				 ROUND(SUM(CASE WHEN a.category2 = 'OPERATING EXPENSE' AND accountId != 12 THEN quantity*unitPrice ELSE 0 END),2) AS opexp, 
				 ROUND(SUM(CASE WHEN a.category1 = 'EXPENSE' AND a.category2 != 'OPERATING EXPENSE' AND accountId != 12 THEN quantity*unitPrice ELSE 0 END),2) AS otherexp, 
				 getCOGS(itemId)*ROUND(SUM(CASE WHEN accountId = 13 THEN quantity ELSE 0 END),2) AS cogs
				FROM transaction_detail d 
				INNER JOIN transaction t ON (d.transactionId = t.componentId) 
				INNER JOIN item i ON (i.componentId = d.itemId) 
				INNER JOIN account a ON (a.componentId = d.accountId) 
				WHERE (t.tdate BETWEEN '2017-08-10' AND '2017-09-10' ) 
				GROUP BY d.itemId ) a";
		
		//echo $salesSummerySQL;
		
		$data ['sales'] = 0.0;
		
		$data ['commission'] = 0.0;
		
		$data ['cogs'] = 0.0;
		
		$data ['otherexp'] = 0.0;
		
		$data ['opexp'] = 0.0;
		
		$querySummery = $this->db->query ( $salesSummerySQL );
		
		foreach ( $querySummery->result () as $row ) {
			
			$data ['sales'] = $row->sales;
			
			$data ['commission'] = $row->commission;
			
			$data ['cogs'] = $row->cogs;
			
			$data ['otherexp'] = $row->otherexp;
			
			$data ['opexp'] = $row->opexp;
		}
		
		$data ['revenue'] = $data ['sales'] + $data ['commission'];
		
		$data ['grossProfit'] = $data ['revenue'] - $data ['cogs'] - $data ['opexp'];
		
		$data ['netProfit'] = $data ['grossProfit'] - $data ['otherexp'];
		
		$data ['revenue'] = number_format ( ( float ) $data ['revenue'], 2 );
		
		$data ['grossProfit'] = number_format ( ( float ) $data ['grossProfit'], 2 );
		
		$data ['netProfit'] = number_format ( ( float ) $data ['netProfit'], 2 );
		
		$data ['sales'] = number_format ( ( float ) $data ['sales'], 2 );
		
		$data ['cogs'] = number_format ( ( float ) $data ['cogs'], 2 );
		
		$data ['commission'] = number_format ( ( float ) $data ['commission'], 2 );
		
		$data ['otherexp'] = number_format ( ( float ) $data ['otherexp'], 2 );
		
		$data ['opexp'] = number_format ( ( float ) $data ['opexp'], 2 );
		
		$salesSQL = "SELECT itemId, i.itemName, 

						ROUND(SUM(CASE WHEN accountId = 13 THEN quantity*unitPrice ELSE 0 END),2) AS sales, 

						ROUND(SUM(CASE WHEN accountId = 8 THEN quantity*unitPrice ELSE 0 END),2) AS commission,  

						ROUND(getCOGS(itemId)*SUM(CASE WHEN accountId = 13 THEN quantity ELSE 0 END),2) AS cogs

					FROM transaction_detail d

    				INNER JOIN transaction t ON (d.transactionId = t.componentId)

					INNER JOIN item i ON (i.componentId = d.itemId) 

					WHERE (accountId = 8 OR accountId = 13) AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )   

    				GROUP BY i.componentId";
		
		$salesquery = $this->db->query ( $salesSQL );
		
		$data ['salesdata'] = $salesquery->result ();
		
		$opexpSQL = "SELECT accountId, a.uniqueCode accountName,

						ROUND(SUM(quantity*unitPrice),2) opexp

					FROM transaction_detail d

    				INNER JOIN transaction t ON (d.transactionId = t.componentId)

					INNER JOIN account a ON (a.componentId = d.accountId)

					WHERE a.category2 = '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "' AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )

					GROUP BY accountId ";
		
		$opexpquery = $this->db->query ( $opexpSQL );
		
		$data ['opexpdata'] = $opexpquery->result ();
		
		$expSQL = "SELECT accountId, a.uniqueCode accountName,

						ROUND(SUM(quantity*unitPrice),2) exp

					FROM transaction_detail d

    				INNER JOIN transaction t ON (d.transactionId = t.componentId)

					INNER JOIN account a ON (a.componentId = d.accountId)

					WHERE a.category1 = '" . Applicationconst::ACCOUNT_CAT1_EXPENSE . "' AND a.category2 != '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "' AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' ) 

					GROUP BY accountId ";
		
		$expquery = $this->db->query ( $expSQL );
		
		$data ['expdata'] = $expquery->result ();
		
		$this->load->view ( 'ledger/profitloss/index.php', $data );
	}
	private function searchBalancesheet() {
		$data = $this->commonTasks ();
		
		$data ['stdate'] = date ( 'Y' ) . '-01-01';
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		$data ['page_title'] = 'Balance Sheet';
		
		$data ['page_name'] = 'home';
		
		$data ['searchAction'] = 'ledger/balancesheet';
		
		$salesSummerySQL = "SELECT

							    SUM(CASE WHEN a.category1 = 'ASSET' THEN d.type*quantity*unitprice END) AS asset,

							    SUM(CASE WHEN a.category1 = 'ASSET' AND a.category2 = 'CURRENT ASSET' THEN d.type*quantity*unitprice END) AS currentAsset,

							    SUM(CASE WHEN a.category1 = 'ASSET' AND ifnull(a.category2,'') != 'CURRENT ASSET' THEN d.type*quantity*unitprice END) AS otherAsset,

							    SUM(CASE WHEN a.category1 = 'LIABILITY' THEN -1*d.type*quantity*unitprice END) AS liability,

							    SUM(CASE WHEN a.category1 = 'LIABILITY' AND a.category2 = 'CURRENT LIABILITY' THEN -1*d.type*quantity*unitprice END) AS currentLiability,

							    SUM(CASE WHEN a.category1 = 'LIABILITY' AND ifnull(a.category2,'') != 'CURRENT LIABILITY' THEN -1*d.type*quantity*unitprice END) AS otherLiability

							FROM transaction_detail d

    						INNER JOIN transaction t ON (d.transactionId = t.componentId)

							INNER JOIN account a ON (d.accountId = a.componentId)

							WHERE (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' ) ";
		
		// echo $salesSummerySQL;
		
		$data ['asset'] = 0.0;
		
		$data ['currentAsset'] = 0.0;
		
		$data ['otherAsset'] = 0.0;
		
		$data ['liability'] = 0.0;
		
		$data ['currentLiability'] = 0.0;
		
		$data ['otherLiability'] = 0.0;
		
		$querySummery = $this->db->query ( $salesSummerySQL );
		
		foreach ( $querySummery->result () as $row ) {
			
			$data ['asset'] = $row->asset;
			
			$data ['currentAsset'] = $row->currentAsset;
			
			$data ['otherAsset'] = $row->otherAsset;
			
			$data ['liability'] = $row->liability;
			
			$data ['currentLiability'] = $row->currentLiability;
			
			$data ['otherLiability'] = $row->otherLiability;
		}
		
		$data ['equity'] = $data ['asset'] - $data ['liability'];
		
		$data ['asset'] = number_format ( ( float ) $data ['asset'], 2 );
		
		$data ['currentAsset'] = number_format ( ( float ) $data ['currentAsset'], 2 );
		
		$data ['otherAsset'] = number_format ( ( float ) $data ['otherAsset'], 2 );
		
		$data ['currentLiability'] = number_format ( ( float ) $data ['currentLiability'], 2 );
		
		$data ['liability'] = number_format ( ( float ) $data ['liability'], 2 );
		
		$data ['otherLiability'] = number_format ( ( float ) $data ['otherLiability'], 2 );
		
		$data ['equity'] = number_format ( ( float ) $data ['equity'], 2 );
		
		$currentAssetSQL = "SELECT

							    d.accountId, a.uniqueCode AS name, SUM(d.type*quantity*unitprice) AS amount

							FROM transaction_detail d

    						INNER JOIN transaction t ON (d.transactionId = t.componentId)

							INNER JOIN account a ON (d.accountId = a.componentId)

							WHERE a.category1 = 'ASSET' AND a.category2 = 'CURRENT ASSET' AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )

							GROUP BY d.accountId";
		
		$currentAssets = $this->db->query ( $currentAssetSQL )->result ();
		
		$data ['currentAssets'] = $currentAssets;
		
		$nonCurrentAssetSQL = "SELECT

							    d.accountId, a.uniqueCode AS name, SUM(d.type*quantity*unitprice) AS amount

							FROM transaction_detail d

    						INNER JOIN transaction t ON (d.transactionId = t.componentId)

							INNER JOIN account a ON (d.accountId = a.componentId)

							WHERE a.category1 = 'ASSET' AND ifnull(a.category2,'') != 'CURRENT ASSET' AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )

							GROUP BY d.accountId";
		
		$nonCurrentAssets = $this->db->query ( $nonCurrentAssetSQL )->result ();
		
		$data ['nonCurrentAssets'] = $nonCurrentAssets;
		
		$currentLiabilitySQL = "SELECT

							    d.accountId, a.uniqueCode AS name, SUM(d.type*quantity*unitprice) AS amount

							FROM transaction_detail d

    						INNER JOIN transaction t ON (d.transactionId = t.componentId)

							INNER JOIN account a ON (d.accountId = a.componentId)

							WHERE a.category1 = 'LIABILITY' AND a.category2 = 'CURRENT LIABILITY' AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )

							GROUP BY d.accountId";
		
		$currentLiabilities = $this->db->query ( $currentLiabilitySQL )->result ();
		
		$data ['currentLiabilities'] = $currentLiabilities;
		
		$nonCurrentLiabilitySQL = "SELECT

							    d.accountId, a.uniqueCode AS name, SUM(d.type*quantity*unitprice) AS amount

							FROM transaction_detail d

    						INNER JOIN transaction t ON (d.transactionId = t.componentId)

							INNER JOIN account a ON (d.accountId = a.componentId)

							WHERE a.category1 = 'LIABILITY' AND ifnull(a.category2,'') != 'CURRENT LIABILITY' AND (t.tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )

							GROUP BY d.accountId";
		
		$nonCurrentLiabilities = $this->db->query ( $nonCurrentLiabilitySQL )->result ();
		
		$data ['nonCurrentLiabilities'] = $nonCurrentLiabilities;
		
		return $data;
	}
	public function balancesheet() 

	{
		$data = $this->searchBalancesheet ();
		
		$data ['search'] = '';
		
		$data ['downloadAction'] = 'ledger/balancesheetpdf';
		
		$this->load->view ( 'ledger/balancesheet/index.php', $data );
	}
	public function balancesheetpdf() 

	{
		$data = $this->searchBalancesheet ();
		
		$this->load->view ( 'ledger/balancesheet/content', $data );
		
		// Get output html
		
		$html = $this->output->get_output ();
		
		// Load library
		
		$this->load->library ( 'dompdf_gen' );
		
		$this->dompdf->load_html ( $html );
		
		$this->dompdf->render ();
		
		$this->dompdf->stream ( "Balancesheet.pdf" );
	}
	function purchase() {
		$data = $this->commonTasks ();
		
		$data ['stdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		$data ['page_title'] = 'Purchase Report';
		
		$data ['page_name'] = 'home';
		
		$data ['searchAction'] = 'ledger/purchase';
		
		$summerysql = "SELECT componentId, uniqueCode, DATE_FORMAT(tdate,'%d %b %y') tdate, ttype, (SELECT n.customerName FROM vtransactiondetails n WHERE n.componentId = v.componentId AND n.accountId = " . Applicationconst::ACCOUNT_HEAD_PURCHASE . "  LIMIT 1) customerName, userId,
    	ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " THEN quantity*unitPrice ELSE 0 END), 2) AS totalamount,
    			SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " THEN quantity ELSE 0 END) AS totalQty,
    			ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_SUSPENSION . " THEN quantity*unitPrice ELSE 0 END), 2) AS commission,
    	ROUND( SUM(CASE WHEN category3 = '" . Applicationconst::ACCOUNT_CAT3_BANK . "' OR category3 = '" . Applicationconst::ACCOUNT_CAT3_CASH . "' THEN quantity*unitPrice ELSE 0 END), 2) AS totalpaid,
    	ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_PAYABLE . "  THEN quantity*unitPrice ELSE 0 END), 2) AS totaldue
    	FROM vtransactiondetails v
    	WHERE (ttype = '" . Applicationconst::TRANSACTION_TYPE_PURCHASE . "' OR ttype = '" . Applicationconst::TRANSACTION_TYPE_PURCHASE_RETURN . "' ) 
			 AND (tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' ) 
    	GROUP BY componentId
    	ORDER BY tdate DESC, componentId DESC";
		
		// echo $summerysql;
		
		$summerydata = $this->db->query ( $summerysql )->result ();
		
		$detail = array ();
		
		foreach ( $summerydata as $row ) {
			
			$detail [$row->componentId] = array ();
		}
		
		$data ['summerydata'] = $summerydata;
		
		$detailsql = "SELECT componentId, itemName, unitprice, quantity
    	FROM vtransactiondetails
    	WHERE (ttype = '" . Applicationconst::TRANSACTION_TYPE_PURCHASE . "' OR ttype = '" . Applicationconst::TRANSACTION_TYPE_PURCHASE_RETURN . "' )  AND accountId = " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " AND (tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' ) ";
		
		$ddata = $this->db->query ( $detailsql )->result ();
		
		foreach ( $ddata as $row ) {
			
			array_push ( $detail [$row->componentId], $row );
		}
		
		$data ['detail'] = $detail;
		
		$this->load->view ( 'ledger/purchase/index.php', $data );
	}
	function sale() {
		$data = $this->commonTasks ();
		
		$data ['stdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'stdate' ) != null)
			
			$data ['stdate'] = $this->input->post ( 'stdate' );
		
		$data ['etdate'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
		
		if ($this->input->post ( 'etdate' ) != null)
			
			$data ['etdate'] = $this->input->post ( 'etdate' );
		
		$data ['page_title'] = 'Sale Report';
		
		$data ['page_name'] = 'home';
		
		$this->load->model('companymodel');
		
		$data['companies'] = $this->companymodel->getAll();
		
		$this->load->model('usersmodel');
		
		$data['users'] = $this->usersmodel->getAll();
		
		$data ['searchAction'] = 'ledger/sale';
		$userId = $this->session->userdata('userid');
		$comrs = $this->db->query ( "SELECT companyId FROM usercompany WHERE userId = $userId")->result ();
		$userCond = "";
		foreach ($comrs as $com) {
			$userCond = " AND v.userId IN (SELECT userId 
					FROM usercompany uc
					INNER JOIN company c ON (uc.companyId = c.componentId)
					WHERE c.hierarchyPath LIKE '%-".$com->companyId."-%') ";
		}
		
		$summerysql = "SELECT componentId, uniqueCode, DATE_FORMAT(tdate,'%d %b %y') tdate, ttype, (SELECT n.customerName FROM vtransactiondetails n WHERE n.componentId = v.componentId AND n.accountId = " . Applicationconst::ACCOUNT_HEAD_SALE . "  LIMIT 1) customerName, userId,
    	ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_SALE . " THEN quantity*unitPrice ELSE 0 END), 2) AS totalamount,
    			SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_SALE . " THEN quantity ELSE 0 END) AS totalQty,salesperson,
    	ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_SALE_DISCOUNT . " THEN quantity*unitPrice ELSE 0 END), 2) AS commission,
    	ROUND( SUM(CASE WHEN category3 = '" . Applicationconst::ACCOUNT_CAT3_BANK . "' OR category3 = '" . Applicationconst::ACCOUNT_CAT3_CASH . "' THEN quantity*unitPrice ELSE 0 END), 2) AS totalpaid,
    	ROUND( SUM(CASE WHEN accountId = " . Applicationconst::ACCOUNT_HEAD_RECEIVABLE . "  THEN quantity*unitPrice ELSE 0 END), 2) AS totaldue
    	FROM vtransactiondetails v
    	WHERE ( ttype = '" . Applicationconst::TRANSACTION_TYPE_SALE . "' OR ttype = '" . Applicationconst::TRANSACTION_TYPE_SALE_RETURN . "' )
			 AND (tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )
		 $userCond  
			 
    	GROUP BY componentId
    	ORDER BY tdate DESC, componentId DESC";
		
		 //echo $summerysql;
		
		$summerydata = $this->db->query ( $summerysql )->result ();
		
		$detail = array ();
		
		foreach ( $summerydata as $row ) {
			
			$detail [$row->componentId] = array ();
		}
		
		$data ['summerydata'] = $summerydata;
		
		$detailsql = "SELECT componentId, itemName, unitprice, quantity
    	FROM vtransactiondetails v
    	WHERE  ( ttype = '" . Applicationconst::TRANSACTION_TYPE_SALE . "' OR ttype = '" . Applicationconst::TRANSACTION_TYPE_SALE_RETURN . "' ) AND accountId = " . Applicationconst::ACCOUNT_HEAD_SALE . " AND (tdate BETWEEN '" . $data ['stdate'] . "' AND '" . $data ['etdate'] . "' )
   			$userCond 
        ";
		
		$ddata = $this->db->query ( $detailsql )->result ();
		
		foreach ( $ddata as $row ) {
			
			array_push ( $detail [$row->componentId], $row );
		}
		
		$data ['detail'] = $detail;
		
		$this->load->view ( 'ledger/sale/index.php', $data );
	}
}

