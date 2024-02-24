<?php

# フィクスチャを使用して初期データをロードできます。
# php bin/console doctrine:fixtures:load

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use App\Entity\YourEntity; // あなたのエンティティに合わせて変更してください。
use League\Csv\Reader; // CSV操作のためのライブラリ

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $csv = Reader::createFromPath('%kernel.project_dir%/path/to/your/file.csv', 'r');
        $csv->setHeaderOffset(0); // CSVのヘッダー行を指定

        $records = $csv->getRecords(); // CSVからデータを読み込む
        foreach ($records as $record) {
            // $entity = new YourEntity();
            // $recordのデータをエンティティに設定
            // $entity->setSomeField($record['column_name']);
            // 他のフィールドも同様に設定

            // $manager->persist($entity);
        }

        $manager->flush();
    }
}
