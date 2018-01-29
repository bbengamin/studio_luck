public function getProducts($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
        LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) 
        WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        
        // name filter used for 'OR' statement between `p.model`, `p.product_id`, `p.name`
        if (!empty($data['filter_name'])) {

            $filters = explode( " ", $data['filter_name']);

            $filtered_names = array_filter($filters, function($each_char) {
                // do not touch
                return !empty(trim($each_char));
            });

            $name_filters_count = count($filtered_names);
            
            $sql .= " AND ";
            if ( $name_filters_count === 1 ) {
                $sql .= $this->processNameFilter( join( " ", $filtered_names ) );
            }
            else {
            
                // for end find
                $counter = 0;
                
                $exploded_request = " (";
                foreach ($filtered_names as $filtered){
                    if ( !empty($filtered) ){
                        $exploded_request .= $this->processNameFilter($filtered);

                        // if we have reached the end of exploded filter
                        // do not paste 'OR' statement at the end of the result sql query
                        if ( $counter !== $name_filters_count - 1 ) {
                            $exploded_request .= " OR ";
                        }

                        ++$counter;
                    }
                }
                $exploded_request .= " )";

                $sql .= $exploded_request;
            }
        }

        if (!empty($data['category_filter'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['category_filter'] . "'";
        }

        if (!empty($data['specials_filter'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special ps)";
        }

        if ( !empty($data['filter_category']) ) {
		    $sql .= " AND cd.name LIKE '" . $data['filter_category'] . "' ";
        }

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

    private  function processNameFilter($name_filter){
        $result_sql = " pd.name LIKE '%" . $this->db->escape($name_filter) . "%'
                        OR p.model LIKE '%" . $this->db->escape($name_filter) . "%'";

        if ( is_numeric($name_filter) ) {
            $result_sql .= " OR p.product_id ='" . $name_filter . "' ";
        }

        return $result_sql;
    }
