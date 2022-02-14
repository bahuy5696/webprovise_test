<?php

class Travel
{
    public function getTravels()
    {
        return json_decode(
            file_get_contents('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/travels'),
            true
        );
    }
}

class Company
{
    public function getCompanies()
    {
        return json_decode(
            file_get_contents('https://5f27781bf5d27e001612e057.mockapi.io/webprovise/companies'),
            true
        );
    }
}

class TestScript
{
    public $company;
    public $travels;

    public $tree;

    public function process()
    {
        $associate = [];
        foreach ($this->company as $company) {
            $associate[$company['id']] = [
                'id' => $company['id'],
                'name' => $company['name'],
                'cost' => 0,
                'children' => [],
                'parent' => $company['parentId']
            ];
        }

        foreach ($this->travels as $travel) {
            if (isset($associate[$travel['companyId']])) {
                $associate[$travel['companyId']]['cost'] += (int)$travel['price'];
            }
        }

        $root = [];
        foreach ($associate as $key => &$node) {
            if ($node['parent'] == '0') {
                $root[] = &$node;
                continue;
            }

            $associate[$node['parent']]['children'][] = &$node;
        }
        return $root;
    }

    public function getData()
    {
        $this->company = (new Company())->getCompanies();
        $this->travels = (new Travel())->getTravels();
    }
}

$test = new TestScript();
$test->getData();
var_dump($test->process());
