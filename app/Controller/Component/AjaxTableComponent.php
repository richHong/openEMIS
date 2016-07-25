<?php
/*
OpenEMIS School
Open School Management Information System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please email contact@openemis.org.
*/

App::uses('FormUtilityHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');

class AjaxTableComponent extends Component {
	private $Form;
	public $components = array('Message');
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->Form = new FormHelper(new View(new Controller()));
	}

	public function tableHtml($table) {
		$max = isset($table['max']) ? $table['max'] : -1;
	
		$html = "<div class=\"form-group\">";
        $html .= "<label class=\"col-md-2 control-label\">{$table['label']}</label>";
        $html .= "<div class=\"col-md-8\">";
        $html .= "<table class=\"table table-bordered ajax-table\" >";
        $html .= "<thead>";
        $html .= "<tr>";
        
        foreach ($table['fields'] as $field) {
			$columnHeader = isset($field['label']) ? $field['label'] : Inflector::humanize($field['field']);
			$html .= "<th>$columnHeader</th>";
        }
        
        $html .= "<th>{$this->Message->getLabel('general.delete')}</th>";
        
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody id=\"{$table['id']}\">";
        
        $min = (isset($table['min']) && $table['min'] > 0) ? $table['min'] : 1;
        
        // Ensure there's always at least 1 row in the table initially even if the min is 0, or the specified min in the array
        for ($i = 0; $i < $min; $i++) {
			$html .= $this->rowHtml($table);
        }
        
        $html .= "</tbody>";
        $html .= "</table>";

        $html .= "<div>";
        $html .= "<a href=\"#\" onclick=\"return AjaxTable.addRow('{$table['id']}', '{$table['addRowUrl']}', $max);\">+ {$table['addLabel']}</a>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        
        return $html;
	}
	
	public function rowHtml($table) {
		$id = uniqid();
		$min = isset($table['min']) ? $table['min'] : 0;
		$html = "<tr id=\"$id\">";
        
        foreach ($table['fields'] as $field) {
			$html .= '<td>';
			$html .= $this->Form->input($field['field'], array('label' => false, 'div' => false, 'between' => false, 'class' => 'form-control'));
			$html .= '</td>';
        }
        
        $html .= "<td><a href=\"#\" onclick=\"return AjaxTable.deleteRow('{$table['id']}', '$id', $min);\">{$this->Message->getLabel('general.delete')}</a></td>";
        $html .= "</tr>";
        
        return $html;
	}
}
