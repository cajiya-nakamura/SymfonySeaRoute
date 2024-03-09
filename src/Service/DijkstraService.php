<?php

namespace App\Service;

// Taniko\Dijkstra\Graphクラスを継承するために必要なuse文。適宜パスを調整してください。
use Taniko\Dijkstra\Graph;

class DijkstraService extends Graph
{
    // ここにDijkstraServiceクラス特有のプロパティやメソッドを追加します。

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function create(): DijkstraService
    {
        return new self();
    }

    // 例: グラフに特定のロジックを用いてエッジを追加するカスタムメソッド
    public function addCustomEdge(string $a, string $b, $distance): self
    {
        // ここにカスタムロジックを実装します。
        // 例として、親クラスのaddメソッドをそのまま使用しますが、特別な条件や加工を加えることが可能です。
        parent::add($a, $b, $distance);
        return $this;
    }

    // その他、DijkstraServiceクラスに必要なメソッドを追加します。
    /**
     * 最大3つの異なるルートを検索するメソッド
     *
     * @param string $from 開始ノード
     * @param string $to 終了ノード
     * @return array 最大3つの異なるルート
     */
    public function searchMultipleRoutes(string $from, string $to): array
    {
        $routes = [];
        $originalNodes = $this->nodes; // 元のノードデータを保持

        for ($i = 0; $i < 3; $i++) {
            try {
                // 最短経路を検索
                $route = $this->search($from, $to);
                if (!empty($route)) {
                    $routes[] = $route;
                    // 見つかった経路のエッジを一時的に削除して、次の検索で異なる経路を強制
                    foreach ($route as $j => $node) {
                        if (isset($route[$j + 1])) {
                            $this->remove($node, $route[$j + 1]);
                        }
                    }
                }
            } catch (\UnexpectedValueException $e) {
                // 経路が見つからない場合はループを終了
                break;
            }
            // ノードデータをリセット
            $this->nodes = $originalNodes;
        }

        // 元のノードデータを復元
        $this->nodes = $originalNodes;

        return $routes;
    }


}

