<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');
 
/**
 * Conformity Table class
 */
class TestimonialsTableConformity extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__tm_testimonials_conformity', 'id_ti', $db);
        }
        
    	public function loadTagsIds($keys = null)
		{
			if (empty($keys)) {
				$keyName = $this->_tbl_key;
				$keyValue = $this->$keyName;

				if (empty($keyValue)) {
					return true;
				}
	
				$keys = array($keyName => $keyValue);
			}
			else if (!is_array($keys)) {
				// Load by primary key.
				$keys = array($this->_tbl_key => $keys);
			}
	
			// Initialise the query.
			$query	= $this->_db->getQuery(true);
			$query->select('id_tag');
			$query->from($this->_tbl);
			$fields = array_keys($this->getProperties());
			
			foreach ($keys as $field => $value)
			{
				// Check that $field is in the table.
				if (!in_array($field, $fields)) {
					$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_CLASS_IS_MISSING_FIELD', get_class($this), $field));
					$this->setError($e);
					return false;
				}
				// Add the search tuple to the query.
				$query->where($this->_db->quoteName($field).' = '.$this->_db->quote($value));
			}
	
			$this->_db->setQuery($query);
			$results = $this->_db->loadObjectList();
			$row = array();
			foreach($results as $result) $row[] = $result->id_tag;
	
			// Check for a database error.
			if ($this->_db->getErrorNum()) {
				$e = new JException($this->_db->getErrorMsg());
				$this->setError($e);
				return false;
			}
			
			return $row;
		}
        
     public function store($updateNulls = false)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// The asset id field is managed privately by this class.
		if ($this->_trackAssets) {
			unset($this->asset_id);
		}

			$stored = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);

		// If the store failed return false.
		if (!$stored) {
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}

		// If the table is not set to track assets return true.
		if (!$this->_trackAssets) {
			return true;
		}

		if ($this->_locked) {
			$this->_unlock();
		}

		//
		// Asset Tracking
		//

		$parentId	= $this->_getAssetParentId();
		$name		= $this->_getAssetName();
		$title		= $this->_getAssetTitle();

		$asset	= JTable::getInstance('Asset');
		$asset->loadByName($name);

		// Re-inject the asset id.
		$this->asset_id = $asset->id;

		// Check for an error.
		if ($error = $asset->getError()) {
			$this->setError($error);
			return false;
		}

		// Specify how a new or moved node asset is inserted into the tree.
		if (empty($this->asset_id) || $asset->parent_id != $parentId) {
			$asset->setLocation($parentId, 'last-child');
		}

		// Prepare the asset to be stored.
		$asset->parent_id	= $parentId;
		$asset->name		= $name;
		$asset->title		= $title;

		if ($this->_rules instanceof JRules) {
			$asset->rules = (string) $this->_rules;
		}

		if (!$asset->check() || !$asset->store($updateNulls)) {
			$this->setError($asset->getError());
			return false;
		}

		if (empty($this->asset_id)) {
			// Update the asset_id field in this table.
			$this->asset_id = (int) $asset->id;

			$query = $this->_db->getQuery(true);
			$query->update($this->_db->quoteName($this->_tbl));
			$query->set('asset_id = '.(int) $this->asset_id);
			$query->where($this->_db->quoteName($k).' = '.(int) $this->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED_UPDATE_ASSET_ID', $this->_db->getErrorMsg()));
				$this->setError($e);
				return false;
			}
		}

		return true;
	}
	
	
	public function deleteWithTag($pk = null, $tagid=0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// If no primary key is given, return false.
		if ($pk === null) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_NULL_PRIMARY_KEY'));
			$this->setError($e);
			return false;
		}

		// If tracking assets, remove the asset first.
		if ($this->_trackAssets) {
			// Get and the asset name.
			$this->$k	= $pk;
			$name		= $this->_getAssetName();
			$asset		= JTable::getInstance('Asset');

			if ($asset->loadByName($name)) {
				if (!$asset->delete()) {
					$this->setError($asset->getError());
					return false;
				}
			}
			else {
				$this->setError($asset->getError());
				return false;
			}
		}

		// Delete the row by primary key.
		$query = $this->_db->getQuery(true);
		$query->delete();
		$query->from($this->_tbl);
		$query->where($this->_tbl_key.' = '.$this->_db->quote($pk));
		$query->where('id_tag = '.$this->_db->quote($tagid));
		$this->_db->setQuery($query);

		// Check for a database error.
		if (!$this->_db->query()) {
			$e = new JException(JText::_('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}

		return true;
	}
}