<?php

use function views\header\render_header;
use function App\Helpers\Functions\is_local;

echo render_header('Overzicht kosten');
?>
<script src="/assets/pdfjs/build/pdf.js"></script>
<script src="/assets/pdfjs/web/viewer.js"></script>

<div class="container" style='padding-bottom:0px;padding-top:30px;'>
   
</div>
<div class="container" id='section-to-print'>
    <?php
    $pdfs = [];

    if (count($costs) >= 1) {
        foreach ($costs as $cost) {

            
            echo "<div id='{$cost->id}' class='cost' style=' page-break-after: always;overflow: visible; display:block;'>
            <div class='info' style='display:inline;'>
            Kosten voor belasting {$year}: &euro; ".($cost->write_off > 0 ? number_format($cost->peryear,2,',','.'): number_format(round(($cost->price / (($cost->btw + 100) / 100)), 2),2,',','.')  ) . "<br>
            Totaal prijs incl btw: &euro; ".number_format($cost->price,2,',','.'). "<br>
            Totaal Prijs excl btw: &euro; ".number_format(round(($cost->price / (($cost->btw + 100) / 100)), 2),2,',','.'). "<br>
            btw percentage: ".$cost->btw."%<br>
            Afschrijving: "; if($cost->write_off>0){
                echo "periode van " . $cost->write_off_years . " jaar. Eerste jaar was ".$cost->firstyear.". Restwaarde is &euro; ".number_format($cost->write_off_rest,2,',','.').".";
            }else{
                echo "nvt";
            }
            echo "<br><br></div>";
            if (strtolower(substr($cost->image, -4)) == '.pdf') {
                echo "<div class='imageviewer' id='{$cost->id}_viewer'></div>";
                $pdfs[] = [$cost->id, $cost->image];
            } else {
                echo "<div class='imageviewer' id='{$cost->id}_viewer' style='display:block;'>
                <img height='auto' style='max-height:900px;max-width:1000px;' src='/uploads/costs/{$cost->image}' />
                </div>";
            }
            echo "</div><br><hr><br>";
            
        }
    } ?>
</div>
<script>
    $(document).ready(function() {

        $('#print_test').click(function() {
            window.print();
        });
        pdfjsLib.GlobalWorkerOptions.workerSrc = '/assets/pdfjs/build/pdf.worker.js';
        load_all_pdfs();

        async function load_all_pdfs() {
            console.log('loading');
            <?php if(count($pdfs)>0){
                foreach($pdfs as $p){
                    echo "await load_pdf('{$p[1]}', {$p[0]});";
                }
            }
            ?>
            
            console.log('Finish loading');
        }
        async function load_pdf(src, id) {
            var numPages = 0;

            // var loadingTask = pdfjsLib.getDocument('/uploads/costs/01-01-2023-8065351672604480688947.pdf');
            var loadingTask = pdfjsLib.getDocument('/uploads/costs/' + src);
            loadingTask.promise.then(function(pdf) {
                numPages = pdf.numPages;
                if (numPages == 1) {
                    console.log('1 pagina in pdf')
                } else {
                    console.log(numPages + ' paginas in pdf');
                }
                for (let i = 1; i <= numPages; i++) {
                    pdf.getPage(i).then(function(page) {
                        render_pdf(page, id);
                    });
                }

            });
        }

        function render_pdf(page, id) {
            var scale = 1.2;
            var viewport = page.getViewport({
                scale: scale,
            });
            // Support HiDPI-screens.
            var outputScale = window.devicePixelRatio || 1;
            var canvas = document.createElement("canvas");
            canvas.style.display = "block";
            var context = canvas.getContext('2d');
            canvas.width = Math.floor(viewport.width * outputScale);
            canvas.height = Math.floor(viewport.height * outputScale);
            canvas.style.width = Math.floor(viewport.width) + "px";
            canvas.style.height = Math.floor(viewport.height) + "px";

            var transform = outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] :
                null;

            var renderContext = {
                canvasContext: context,
                transform: transform,
                viewport: viewport
            };
            page.render(renderContext);
            document.getElementById(id + '_viewer').appendChild(canvas);
        }
    });
</script>
</body>

</html>