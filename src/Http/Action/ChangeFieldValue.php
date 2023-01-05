<?php

namespace OEngine\Core\Http\Action;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OEngine\Core\Support\Core\ActionBase;
use OEngine\Core\Support\Core\DataInfo;

class ChangeFieldValue extends ActionBase
{
    public function DoAction()
    {
        if (method_exists($this->component, 'getModel')) {

            Log::info($this->param);
            $message = getValueByKey($this->param, 'message');
            if ($message)
                $this->component->showMessage(__($message));
            $model = $this->component->getModel();
            $model = $model->where(getValueByKey($this->param, 'key', 'id'), getValueByKey($this->param, 'id'));
            $elModel = $model->first();

            if ($elModel == null) {
                $this->component->showMessage(__('core::message.not-founds'));
                return;
            }
            if ($elModel instanceof Model) {
                $elModel->{getValueByKey($this->param, 'field', 'status')} =  getValueByKey($this->param, 'value', 0);
                $elModel->save();
            } else if ($elModel instanceof DataInfo) {
                $elModel[getValueByKey($this->param, 'field', 'status')] =  getValueByKey($this->param, 'value', 0);
            }
            $this->component->refreshData();
        }
    }
}
