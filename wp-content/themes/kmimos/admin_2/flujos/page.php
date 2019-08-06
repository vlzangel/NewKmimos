<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
<link rel='stylesheet' type='text/css' href='<?= getTema() ?>/admin_2/<?= $modulo ?>/css.css?v=<?= time() ?>'>
<script src='<?= getTema(); ?>/admin_2/<?= $modulo ?>/js.js?v=<?= time() ?>'></script>

<script src='<?= getTema(); ?>/admin_2/recursos/arbol/go.js'></script>
<?php
    global $wpdb;

    $campaings = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada ASC");
    $flujos = [];
    foreach ($campaings as $key => $campaing) {
        $data = json_decode($campaing->data);
        if( $data->hacer_despues+0 == 0 ){
            $flujos[ $campaing->id ] = [
                $data->data->titulo
            ];
        }else{
            $data = json_decode($campaing->data);
            $flujos[ $campaing->id ] = [
                $data->data->titulo,
                $data->campaing_anterior,
                $data->campaing_despues_no_abre,
            ];
        }
    }
    $grafo = '';
    foreach ($flujos as $key => $data) {
        if( count($data) == 1 ){
            $grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe" },';
        }else{
            if( $data[2] == "si" ){
                $grafo .= '{ key: "'.$key.'_condicional", text: "SI", fill: "#ccc", stroke: "#4d90fe", parent: '.$data[1].' },';
                $grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe", parent: "'.$key.'_condicional" },';
            }else{
                $grafo .= '{ key: "'.$key.'_condicional", text: "NO", fill: "#ccc", stroke: "#4d90fe", parent: '.$data[1].' },';
                $grafo .= '{ key: '.$key.', text: "'.$data[0].'", fill: "#ccc", stroke: "#4d90fe", parent: "'.$key.'_condicional" },';
            }
        }
    }
?>
<div id="myDiagramDiv" style="border: 1px solid black; width: calc( 100% - 20px ); height: calc( 100vh - 70px ); position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0); cursor: auto; margin: 20px 0px 0px;"></div>
<div class="mask"></div>
<style type="text/css">
    .mask {
        position: fixed;
        top: 53px;
        left: 181px;
        display: inline-block;
        width: 200px;
        height: 70px;
        background-color: #FFF;
        z-index: 999;
    }
</style>
<script id="code">
    function init() {
        if (window.goSamples) goSamples();
        var $ = go.GraphObject.make;
        myDiagram =
        $(go.Diagram, "myDiagramDiv",
            {
                allowCopy: false,
                allowDelete: false,
                allowMove: false,
                initialAutoScale: go.Diagram.Uniform,
                layout:
                $(FlatTreeLayout,
                    {
                        angle: 90,
                        compaction: go.TreeLayout.CompactionNone
                    }
                ),
                "undoManager.isEnabled": false
            }
        );

        myDiagram.nodeTemplate =
        $(
            go.Node, 
            "Vertical",
            { selectionObjectName: "BODY" },
            $(go.Panel, "Auto", { name: "BODY" },
            $(
                go.Shape, 
                "RoundedRectangle",
                new go.Binding("fill"),
                new go.Binding("stroke")
            ),
            $(
                go.TextBlock,
                { font: "bold 12pt Arial, sans-serif", margin: new go.Margin(4, 2, 2, 2) },
                new go.Binding("text"))
            ),
            $(
                go.Panel,
                { height: 17 },
                $("TreeExpanderButton")
            )
        );

        myDiagram.linkTemplate =
        $(
            go.Link,
            $( go.Shape, { strokeWidth: 1.5 } )
        );

        var nodeDataArray = [
            <?= $grafo ?>
        ];

        myDiagram.model = $(go.TreeModel, { nodeDataArray: nodeDataArray }); 
    }

    function FlatTreeLayout() {
        go.TreeLayout.call(this);
    }
    go.Diagram.inherit(FlatTreeLayout, go.TreeLayout);

    FlatTreeLayout.prototype.commitLayout = function() {
        go.TreeLayout.prototype.commitLayout.call(this);
        var y = -Infinity;
        this.network.vertexes.each(function(v) {
            y = Math.max(y, v.node.position.y);
        });
        this.network.vertexes.each(function(v) {
            if (v.destinationEdges.count === 0) {
                v.node.position = new go.Point(v.node.position.x, y);
                v.node.toEndSegmentLength = Math.abs(v.centerY - y);
            } else {
                v.node.toEndSegmentLength = 10;
            }
        });
    };

    jQuery(document).ready(function() {
        init();
    });
</script>