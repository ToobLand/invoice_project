<?php

namespace App\Helpers\Functions;

function is_local()
{
    if ($_SERVER["HTTP_HOST"] == "localhost:8888") {
        $local = true;
    } else {
        $local = false;
    }
    return $local;
}
function build_form($action = false, $return = '/', $array = false, $button = 'opslaan')
{
    if ($action && is_array($array)) {
        $output_html = '';
        $output_script = '<script>
        $(document).ready(function(){
            $("#datepicker").datepicker({
                multidate: true,
                format: "yyyy-mm-dd",
            }).on("changeDate", function(e) {
                
                $(this).find(".input-group-addon .count").text(" " + e.dates.length);
            });
           $("#send_form").on("click",function(e){
            e.preventDefault();
            let data={};
            ';
        foreach ($array as $index => $input) {
            if ($input['type'] == 'date') {
                $output_html .= '<div class="form-group">
                <label for="input_' . $index . '">' . $input['label'] . '</label>
                <input type="date" class="form-control" id="input_' . $index . '" placeholder="' . $input['placeholder'] . '">
              </div>';
                $output_script .= ' data["' . $input['post'] . '"]= $("#input_' . $index . '").val();';
            }
            if ($input['type'] == 'date_multi') {
                $output_html .= 'Selecteer (meerdere) data<br/><div class="input-group date form-group" id="datepicker">
                <input type="text" class="form-control" id="Dates" name="Dates" placeholder="Select days" required />
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><span class="count"></span></span>
            </div>';
                $output_script .= ' data["' . $input['post'] . '"]= $("#Dates").val();';
            }
            if ($input['type'] == 'text') {
                $output_html .= '<div class="form-group">
                <label for="input_' . $index . '">' . $input['label'] . '</label>
                <input type="text" class="form-control" id="input_' . $index . '" placeholder="' . $input['placeholder'] . '">
              </div>';
                $output_script .= ' data["' . $input['post'] . '"]= $("#input_' . $index . '").val();';
            }
            if ($input['type'] == 'number') {
                $output_html .= '<div class="form-group">
                <label for="input_' . $index . '">' . $input['label'] . '</label>
                <input type="number" class="form-control" id="input_' . $index . '" placeholder="' . $input['placeholder'] . '">
              </div>';
                $output_script .= ' data["' . $input['post'] . '"]= $("#input_' . $index . '").val();';
            }
            if ($input['type'] == 'select') {
                $output_html .= '<div class="form-group">
                <label for="input_' . $index . '">' . $input['label'] . '</label>
                <Select class="custom-select category" id="input_' . $index . '">';
                foreach ($input['options'] as $option) {
                    $output_html .= "<option value='{$option['value']}'>{$option['title']}</option>";
                }
                $output_html .= '</Select>
              </div>';
                $output_script .= ' data["' . $input['post'] . '"]= $("#input_' . $index . ' option:selected").val();';
            }
            if ($input['type'] == 'radio') {
                $output_html .= '<div class="form-group">
                <label for="">' . $input['label'] . '</label><br>';
                foreach ($input['group'] as $radio) {
                    $output_html .= '<div class="form-check">
                    <input class="form-check-input" type="radio" name="' . $input['post'] . '" value="' . $radio['value'] . '" id="flexRadioDefault1">
                    <label class="form-check-label input_' . $index . '" for="input_' . $index . '">
                    ' . $radio['title'] . '
                    </label></div>';
                }
                $output_html .= '</div>';
                $output_script .= ' data["' . $input['post'] . '"]= $(".input_' . $index . ':checked").val();';
            }
        }
        $output_html .= "<button type='submit' id='send_form' class='btn btn-primary'>{$button}</button>";
        $output_script .= '
        data = JSON.stringify(data);
        
        $.post("' . $action . '", {data:data}, function(response, status){
            if(status=="success"){
                $("#send_form").prop("disabled", true);
            }
            
            delete data;
            message(response);
                          setTimeout(function(){
                            window.location.replace("' . $return . '");
                          },1600);
            
            
          });
    
    }) }); </script>';

        return $output_html . $output_script;
        //return $output_html;
    } else {
        return false;
    }
}
