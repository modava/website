<?php

namespace modava\website\models\query;

use modava\website\models\WebsiteInfo;

/**
 * This is the ActiveQuery class for [[WebsiteInfo]].
 *
 * @see WebsiteInfo
 */
class WebsiteInfoQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([WebsiteInfo::tableName() . '.status' => WebsiteInfo::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([WebsiteInfo::tableName() . '.status' => WebsiteInfo::STATUS_DISABLED]);
    }

    public function sortDescById()
    {
        return $this->orderBy([WebsiteInfo::tableName() . '.id' => SORT_DESC]);
    }
}
