<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `common\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'quantity'], 'integer'],
            [['invoice', 'product_id', 'transfer_type', 'date', 'store'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find()->with(['products', 'customers']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['=', 'invoice', $this->invoice])
            ->andFilterWhere(['=', 'product_id', $this->product_id])
            ->andFilterWhere(['=', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'transfer_type', $this->transfer_type])
            ->andFilterWhere(['like', 'store', $this->store]);

        return $dataProvider;
    }
}
