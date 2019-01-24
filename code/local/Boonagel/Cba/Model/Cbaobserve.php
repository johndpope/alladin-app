<?php

class Boonagel_Cba_Model_Cbaobserve {

    public function updateTarget($observer) {
        //customer_id
        $event = $observer->getEvent();

        //getter method
        $customerId = $event->getCustomerId();

        //get the conditions
        $allConditions = Mage::getModel('alpesa/alpesacondition')->getCollection();

        if ($allConditions->count() > 0) {

            //confirm if user falls in either of the targets by getting all user points and summing them up
            $alpesaTotalActualPoints = Mage::helper('Boonagel_Cba')->sumColVals('alpesa/alpesapoints', array('status' => 'eq,1'), 'points');

            //compare the condition target with the summed up points
            foreach ($allConditions as $condition) {
                if ($alpesaTotalActualPoints >= $condition->points_target) {
                    //conditional target achieved
                    //confirm if a log already exists for this customer's target
                    $this->_confirmTargetLogUpdate($condition, $allConditions, $customerId);

                    //award points accordingly
                }
            }
        }
    }

    /*     * update the wallet that an actual point has been updated* */

    public function updateActualWalletPoints($observer) {
        //get the customer_id,point_id
        $event = $observer->getEvent();

        //getter method
        $customerId = $event->getCustomerId();
        $pointId = $event->getPointId();

        //fetch that specific point and get the points
        $alpesapoints = Mage::getModel('alpesa/alpesapoints');
        $alpesapoints->load($pointId);

        if (count($alpesapoints) == 1) {
            $points = $alpesapoints->points;

            //update the wallet where the userid already exists by adding the actual points
            //$alpesawallet->setActualPoints($alpesawallet->getActualPoints + $points);
            $updatedAt = now();
            $createdAt = now();

            $currentPoints = 0;
            /*             * get the current points* */
            $alpesawalleted = Mage::getModel('alpesa/alpesawallet');
            $alpesawalleted->load($customerId, 'user_id');
            if (count($alpesawalleted) == 1) {
                $currentPoints = $alpesawalleted->actual_points;
            }

            $totalPoints = $currentPoints + $points;
            $alpesawallet = Mage::getModel('alpesa/alpesawallet');
            $alpesawallet->load($customerId, 'user_id');
            $alpesawallet->setUserId($customerId);
            $alpesawallet->setActualPoints($totalPoints);
            $alpesawallet->setUpdatedAt(now());
            $alpesawallet->setcreatedAt(now());
            $dbdata = $alpesawallet->save();
        }
    }

    /*     * confirm,compute and update logs and points on conditions achieved* */

    private function _confirmTargetLogUpdate($condition, $allConditions, $customerId) {
        //confirm that the log does not exist to continue execution
        $logCount = $this->_fetchSpecificCustomerLogCount($customerId, $condition->points_target);

        if ($logCount == 0) {

            //compute the specific conditions
            $canAward = $this->_computeConditions($condition, $allConditions, $customerId);
            //TODO
            if ($canAward == true) {
                //award actual points if conditions are met and
                $configData = Mage::helper('Boonagel_Cba')->getConfigData();
                 $amount = Mage::helper('Boonagel_Cba')->pointPriceConversion($configData,$condition->points_reward,'','pnt-curr'); 
                 
                 $alpesapoints = Mage::getModel('alpesa/alpesapoints');
                 $alpesapoints->getData();
                 $alpesapoints->setUserId($customerId);
                 $alpesapoints->setAmount($amount);
                 $alpesapoints->setStatus(1);
                 $alpesapoints->setPoints($condition->points_reward);
                 $alpesapoints->setUpdatedAt(now());
                 $alpesapoints->setcreatedAt(now());
                 $dbpointsdata = $alpesapoints->save();
                 
                //update the logs table to indicate this condition has been met.
                 $alpesatarget = Mage::getModel('alpesa/alpesatarget');
                 $alpesatarget->getData();
                 $alpesatarget->setUserId($customerId);
                 $alpesatarget->setPointTarget($condition->points_target);
                 $alpesatarget->setUpdatedAt(now());
                 $alpesatarget->setcreatedAt(now());
                 $alpesatargetdata = $alpesatarget->save();
                 
                //initiate an event that updates the actualpoints
                //trigger an event to process actual points
                Mage::helper('Boonagel_Cba')->triggerUpdateActualPoints($customerId,$dbpointsdata->getId());
            }
        }
    }

    /*     * fetch specific customer log for points reward* */

    private function _fetchSpecificCustomerLogCount($customerId, $target) {

        $logCounted = 0;

        $alpesatarget = Mage::getModel('alpesa/alpesatarget')->getCollection()
                ->addFieldToFilter('user_id', array('eq' => $customerId))
                ->addFieldToFilter('point_target', array('eq' => $target))
                ->setPageSize(1);

        $logCounted = $alpesatarget->count();

        return $logCounted;
    }

    /*     * compute the conditions* */

    private function _computeConditions($condition, $allConditions, $customerId) {

        $canAward = false;
        $thisCondionRelated = array();
        foreach ($allConditions as $specificCondition) {
            if ($condition->config_id === $specificCondition->config_id) {
                //add to the array
                $thisCondionRelated[] = $specificCondition;
            }
        }

        if (count($thisCondionRelated) > 0) {

            //get all the values that meet that per visit spending
            //model with per_visit_spending,log_type=spending_session
            $perVisitSpending = $condition->per_visit_spending;
            $alpesauser = Mage::getModel('alpesa/alpesauser')->getCollection()
                    ->addFieldToFilter('user_id', array('eq' => $customerId))
                    ->addFieldToFilter('session_amount', array('gteq' => $perVisitSpending))
                    ->addFieldToFilter('complete_transaction', array('eq' => 1))
                    ->addFieldToFilter('log_type', array('eq' => 'spending-session'));

            $totalVisits = $alpesauser->count();
            
            if($totalVisits > 0){
                $canAward = $this->_actualComparisons($thisCondionRelated, $customerId, $totalVisits);
            }
            
        }

        return $canAward;
    }

    /*     * actual operational types and comparisons* */

    private function _actualComparisons($actualConditions, $customerId, $totalVisits) {
        $awardthem = false;

        $operator = '';
        $counter = 0;
        $probFlag = null;
        foreach ($actualConditions as $singleCondtion) {
            //extract the visits and conditions checking each at a time 
            //condition_operator=$innerFlag
            $counter++;

            if ($counter == 1) {
                $operator = $singleCondtion->condition_operator;
                $probFlag = null;
            }

            $innerFlag = $this->_computeConditionalOperators($singleCondtion, $totalVisits, $operator, $probFlag);

            $operator = $innerFlag[1];
            $probFlag = $innerFlag[0];

            $awardthem = $innerFlag[0];
        }
        
        return $awardthem;
    }

    /*     * computE the conditional_operators* */

    private function _computeConditionalOperators($singleCondtion, $totalVisits, $operator, $probFlag) {
        //eq,lt,gt,leq,geq
        $flagVal = array();
        $innerflag = false;
        switch ($singleCondtion->condition_scope) {

            case 'eq':
                $innerflag = ($totalVisits == $singleCondtion->visits);
                break;

            case 'lt':
                $innerflag = ($totalVisits < $singleCondtion->visits);
                break;

            case 'gt':
                $innerflag = ($totalVisits > $singleCondtion->visits);
                break;

            case 'leq':
                $innerflag = ($totalVisits <= $singleCondtion->visits);
                break;

            case 'geq':
                $innerflag = ($totalVisits >= $singleCondtion->visits);
                break;
        }
        
        
        if ($probFlag != null) {
            if ($operator == 'and') {
                $flagVal[0] = $probFlag && $innerflag;
            }

            if ($operator == 'or') {
                $flagVal[0] = $probFlag || $innerflag;
            }
        } else {
            $flagVal[0] = $innerflag;
        }
        
        $flagVal[1] = $singleCondtion->condition_operator;

        return $flagVal;
    }

}
