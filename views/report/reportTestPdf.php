<?php

use function views\header\render_header;
use function App\Helpers\Functions\is_local;

echo render_header('Overzicht kosten');
?>
<script src="/assets/pdfjs/build/pdf.js"></script>
<script src="/assets/pdfjs/web/viewer.js"></script>

<div class="container" style='padding-bottom:0px;padding-top:30px;'>
    <button id='print_test' class='btn btn-secondary'>Printen</button>
</div>
<div class="container" id='section-to-print'>
    <?php
    $pdfs = [];

    if (count($costs) >= 1) {
        foreach ($costs as $cost) {

            echo "<div id='{$cost->id}' class='cost'>
            <div class='info'>
            Kosten voor belasting {year}: 1232<br>
            Totaal prijs incl btw: 1231231,12<br>
            Totaal Prijs excl btw: 123123<br>
            btw percentage: bladiebla<br>
            categorie: bladieblad<br>
            Afschrijving: periode 3 jaar, 120 per jaar, restwaarde 2400, 1e afschrijving in 2022<br>
            </div>";
            if (strtolower(substr($cost->image, -4)) == '.pdf') {
                echo "<div class='imageviewer' id='{$cost->id}_viewer'></div>";
                $pdfs[] = [$cost->id, $cost->image];
            } else {
                echo "<div class='imageviewer' id='{$cost->id}_viewer'></div>";
            }
            echo "</div>";
            
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
            var scale = 1;
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