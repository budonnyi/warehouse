<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `app\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            [['invoice', 'date', 'bill', 'bill_date', 'contract', 'contract_date', 'document_type', 'store', 'comment'], 'safe'],
            [['total_amount', 'total_amount_sek', 'sek_rate', 'custom_taxes', 'transport_fee', 'brocker_fee', 'additional_cost'], 'number'],
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
        $query = Invoice::find();

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
            'date' => $this->date,
            'bill_date' => $this->bill_date,
            'contract_date' => $this->contract_date,
            'customer_id' => $this->customer_id,
            'quantity' => $this->quantity,
            'total_amount' => $this->total_amount,
            'total_amount_sek' => $this->total_amount_sek,
            'sek_rate' => $this->sek_rate,
            'custom_taxes' => $this->custom_taxes,
            'transport_fee' => $this->transport_fee,
            'brocker_fee' => $this->brocker_fee,
            'additional_cost' => $this->additional_cost,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'invoice', $this->invoice])
            ->andFilterWhere(['like', 'bill', $this->bill])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'store', $this->store])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
