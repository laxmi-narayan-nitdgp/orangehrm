<?php

/*
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

/**
 * Get workshift for a given employee
 */
class getWorkshiftAjaxAction extends sfAction {

    private $employeeService;
    
    /**
     *
     * @return EmployeeService
     */
    public function getEmployeeService() {
        if (!($this->employeeService instanceof EmployeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    /**
     *
     * @param EmployeeService $service 
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }  
    
    public function execute( $request ){
        sfConfig::set('sf_web_debug', false);
        sfConfig::set('sf_debug', false);
        
        $empNumber = $request->getParameter('empNumber');
        
        $employeeWorkShift = $this->getEmployeeService()->getEmployeeWorkShift($empNumber);
        
        if ($employeeWorkShift != null) {
            $workShiftLength = $employeeWorkShift->getWorkShift()->getHoursPerDay();
        } else {
            $workShiftLength = WorkShift :: DEFAULT_WORK_SHIFT_LENGTH;
        }

        $result = array('workshift' => $workShiftLength);
        $response = $this->getResponse();
        $response->setHttpHeader('Expires', '0');
        $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
        $response->setHttpHeader("Cache-Control", "private", false);
            
        echo json_encode($result);
        
        return sfView::NONE;
    }
    
    public function getWorkWeekList(){
        return $this->getWorkWeekService()->getWorkWeekOfOperationalCountry(null);
    }
    
    

}

?>
