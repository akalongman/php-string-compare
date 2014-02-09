<?php
/**
 * This class compares two strings and outputs the similarities  as percentage
 *
 * @author Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 */
class StringCompare
{
	private $_str1 = '';
	private $_str2 = '';
	private $_words1 = array();
	private $_words2 = array();
	private $_percent = null;

	// remove extra spaces, tabs and new lines
	private $_remove_extra_spaces = true;

	// remove html tags
	private $_remove_html_tags = true;

	// remove punctuation symbols
	private $_remove_punctuation = true;

	// punctuation symbols
	private $_punctuation_symbols = array('.', ',', '/', '-', '$', '*', ':', ';', '!', '?', '|', '\\', '_', '<', '>', '#', '~', '"', '\'', '^', '(', ')', '=', '+');


	/**
	 *Contructor function
	 *
	 *@param string $str1
	 *@param string $str2
	 *@return string
	 */
	public function __construct($str1, $str2, $params = array())
	{
		if (!empty($params['remove_html_tags'])) {
			$this->_remove_html_tags = $params['remove_html_tags'];
		}

		if (!empty($params['remove_extra_spaces'])) {
			$this->_remove_extra_spaces = $params['remove_html_tags'];
		}

		if (!empty($params['remove_punctuation'])) {
			$this->_remove_punctuation = $params['remove_punctuation'];
		}

		if (!empty($params['punctuation_symbols'])) {
			$this->_punctuation_symbols = $params['punctuation_symbols'];
		}



		if ($this->_remove_html_tags) {
			$str1 = strip_tags($str1);
			$str2 = strip_tags($str2);
		}


		if ($this->_remove_punctuation && count($this->_punctuation_symbols)) {
			$str1 = str_replace($this->_punctuation_symbols, '', $str1);
			$str2 = str_replace($this->_punctuation_symbols, '', $str2);
		}

		if ($this->_remove_extra_spaces) {
			$str1 = preg_replace('#\s+#u', ' ', $str1);
			$str2 = preg_replace('#\s+#u', ' ', $str2);
		}

		$this->_str1 = $str1 = trim($str1);
		$this->_str2 = $str2 = trim($str2);
		$this->_words1 = explode(' ', $str1);
		$this->_words2 = explode(' ', $str2);
	}

	/**
	 *Function to compare two strings and return the similarity in percentage
	 *
	 *@access public
	 *@return object
	 */
	public function process()
	{
		if (!is_null($this->_percent)) {
			return false;
		}

		$str1 = $this->_str1;
		$str2 = $this->_str2;

		if ($str1 == '') {
			trigger_error('First string can not be blank', E_USER_ERROR);
			return false;
		} else if ($str2 == '') {
			trigger_error('Second string can not be blank', E_USER_ERROR);
			return false;
		}

		$tmp1 = $this->_words1;
		$c1 = count($tmp1);

		$tmp2 = $this->_words2;
		$c2 = count($tmp2);

		$count = $c1;
		$t1 = $tmp1;
		$t2 = $tmp2;
		if ($c2 > $c1) {
			$count = $c2;
		}
		$result = array();

		for ($i = 0; $i < $count; $i++) {
			$tt1 = isset($t1[$i]) ? $t1[$i] : '';
			$tt2 = isset($t2[$i]) ? $t2[$i] : '';
			if ($tt1 == $tt2) {
				$result[] = 1;
				$resultSame[] = 0;
			} else {
				$result[] = 0;
				$resultSame[] = levenshtein($tt1, $tt2);
			}
		}
		$countArray = array_count_values($result);
		$one = 0;
		$zero = 0;
		if (isset($countArray[0])) {
			$zero = $countArray[0];
		}
		if (isset($countArray[1])) {
			$one = $countArray[1];
		}
		if ($one === 0) {
			$percent = number_format(0, 2);
		} else if ($zero === 0) {
			$percent = number_format(100, 2);
		} else {
			$per = ($one / ($one + $zero)) * 100;
			$percent = number_format($per, 2);
		}
		if ($c1 === $c2) {
			$words1 = array_diff_assoc($tmp1, $tmp2);
			$words2 = array_diff_assoc($tmp2, $tmp1);
			$sum = array_sum($resultSame);
			$sum = ($sum / 100);
			$percent = ($percent - $sum);
		}
		$this->_percent = $percent;
		return $this;
	}


	/**
	 *Function to compare two strings and return the similarity in percentage
	 *
	 *@access public
	 *@return float
	 */
	public function getSimilarityPercentage()
	{
		$this->process();
		return $this->_percent;
	}


	/**
	 *Function to compare two strings and return the difference in percentage
	 *
	 *@access public
	 *@return float
	 */
	public function getDifferencePercentage()
	{
		$this->process();
		return 100 - $this->_percent;
	}
}
