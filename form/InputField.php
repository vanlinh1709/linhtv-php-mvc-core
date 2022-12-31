<?php

namespace linhtv\phpmvc\form;

use linhtv\phpmvc\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public string $type;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function __toString()
    {
        return sprintf('
          <div class="form-group">
            <label>%s</label>
            %s
            <div class="invalid-feedback">
                %s
            </div>
          </div>
         ', $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );//sprintf
    }
    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
    public function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : '');
    }
}