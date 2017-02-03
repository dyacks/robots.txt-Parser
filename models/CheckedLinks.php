<?php

namespace App\Models;

use App\Models\AbstractModel;

/**
 * Class CheckedLinksModel
 *
 * @property $id
 * @property $link
 * @property $datetime
 */
class CheckedLinks extends AbstractModel {

    protected static $table = 'checkedLinks';

}