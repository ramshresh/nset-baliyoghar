<style type="text/css">

    #main-organizer-block,#implementing-partner-block{
        width:200px;
        height:90px;
        overflow-y: scroll;
        padding:10px;
        border:1px solid #ccc;
        border-radius:5px;
    }
    h1,h2,h3,h4,h5,h6,form,table{margin:0px;}
</style>

<!-- import script for popup -->

<script src="../js/popup/jquery.ui.core.js"></script>
<script src="../js/popup/jquery.ui.widget.js"></script>
<script src="../js/popup/jquery.ui.mouse.js"></script>
<script src="../js/popup/jquery.ui.draggable.js"></script>
<script src="../js/popup/jquery.ui.position.js"></script>
<script src="../js/popup/jquery.ui.resizable.js"></script>
<script src="../js/popup/jquery.ui.button.js"></script>
<script src="../js/popup/jquery.ui.dialog.js"></script>

<script type="text/javascript">
  /*  $(document.body).on('click','#addnewvdc',function(){
        $( "#dialog" ).dialog();
        $('#location_code_text').val('');
        $('#location_text').val('');
    });
    */
    $(document.body).on('click','#location_savebtn',function(){
        var location = $.trim($('#location_text').val());
        if(location==''){
            alert('Location field is blank');
        }else{
            var location_code = $('#location_code_text').val();
            var level_id = $('#hidden_levelid_identifier').val();
            //------------------
            $.ajax({						
                type: "POST",
                url: "../Home/addCoverageLocation",
                data: {
                    coverage_location:location,
                    coverage_location_code:location_code,
                    coverage_level:level_id
                },
                cache: false,
                error: function(xhr, status, error) {
                    alert('Error !\n Please try again.\n(Please check your internet connection.)');
                },
                success: function (msg) {
                    var success = $.trim(msg);
                    if(success == 'yes'){
                        //if added to database , load the newly added vdc into dropdown and set the value
                        $('#coverage_location').append('<option value="'+location+'">'+location+'</option>');
                        $('#coverage_location').val(location);

                        $('.ui-dialog').remove(); //dismiss the popup
                       // $('#dialog').html(''); // reset the form div of popup
                    }
                    else{
                        $('#dialog').html('<p class="text-error size11"><b>Sorry ! your request failed.</b></p>'); // reset the form div of popup
                    }
                }
            });
        }
        //-----------------
    });
    
    $(document.body).on('click','#location_cancelbtn',function(){
        $('.ui-dialog').remove(); //dismiss the popup
       // $('#dialog').html(''); // reset the form div of popup
    });
</script>
<!-- end script import -->

<script type="text/javascript">
    $(document).ready(function(){
        //either main organizer can be selected or implementing partner but not both at the same time -eg , vdc, vdc 
//        $(document.body).on('click','input[id^=mainorg_]',function(){
//            var id =$(this).attr('id');
//            var array = id.split("_");
//            if($(this).is(':checked')){
//                $('#implpartner_'+array[1]).prop('checked', false);
//            }
//        });
//        $(document.body).on('click','input[id^=implpartner_]',function(){
//            var id =$(this).attr('id');
//            var array = id.split("_");
//            if($(this).is(':checked')){
//                $('#mainorg_'+array[1]).prop('checked', false);
//            }
//        });
        
    });
</script>
<div class="container">
    <table style="border:1px solid #CCC;margin-top:30px" width="100%" class="getBg">
        <tr><td style="padding:20px">
                <h3 class="uppercase nicefont nicecolor"><b class="icon-globe"></b> &nbsp;Event Entry </h3>
                <hr />               
                <?php echo validation_errors(); ?>
                <span style="color:green"><?php if (isset($insert)) echo $insert . "<br />"; ?></span>    
                <?php echo form_open('Event/createEvent', array('id' => 'event_entry_form', 'name' => 'event_entry_form')); ?>
                <input type="hidden" name="identifier" value="insert" />
                <table width="" border="0">
                    <tr>
                        <td ><label for="event_title">Title : </label></td>
                        <td colspan="4">
                            <input type="text" style="width:712px" id="event_title" name="event_title" placeholder="Enter title"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <table border="0" width="100%">
                                <tr>
                                    <td style="width:148px"><label for="event_start_date">Start date : </label></td>
                                    <td style="width:220px;">
                                        <input type="text" name="event_start_date"  id="event_start_date" class="datepicker" placeholder="Enter start date" style="width:150px;"/>
                                    </td>
                                    <td style="width:90px"><label for="event_end_date">End date : </label></td>
                                    <td style="width:202px">
                                        <input type="text" name="event_end_date"  id="event_end_date" class="datepicker" placeholder="Enter end date" style="width:150px;"/>
                                    </td>
                                    <td style="width:100px"><label for="event_year">Event year : </label></td>
                                    <td>
                                        <select name="event_year" id="event_year" style="width:107px;">
                                            <option value="2012">2012</option>
                                            <option value="2013" selected>2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:150px"><label for="event_course_category">Event type : </label></td>
                        <td style="width:300px">
                            <select name="event_course_category" id="event_course_category" >
                                <option value="">-- SELECT --</option>
                                <?php
                                if (isset($CourseContent)) {
                                    echo $CourseContent;
                                }
                                ?>
                            </select>
                            <span style="width:20px;display:inline-block">
                                <img src ="../img/loading.gif" style="margin-top: -10px; padding:5px;display:none" id="loading_image"/>
                            </span>
                        </td>
                        <td style="width:50px" ><span class="text-info"><b>&gt;&gt;</b></span></td>
                        <td style="width:150px"><label for="event_course_subcategory">Course : </label></td>
                        <td style="width:300px">
                            <span id="getSubCourse">
                                <select name="event_course_subcategory" id="event_course_subcategory" disabled="disabled">
                                    <option value="">-- SELECT --</option>
                                </select>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td ><label>Coverage level : </label></td>
                        <td >
                            <select name="coverage_level" id="event_level">
                                <option value="">Select</option>
                                <?php
                                if (isset($coverage_level_array) && count($coverage_level_array) > 0) {
                                    for ($i = 0; $i < count($coverage_level_array); $i++) {
                                        echo '<option value="' . $coverage_level_array[$i][0] . '">' . $coverage_level_array[$i][1] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <span style="width:20px;display:inline-block">
                                <img id="loading_image1" style="margin-top: -10px; padding: 5px; display: none;" src="../img/loading.gif">
                            </span>
                        </td>
                        <td style="width:50px" ><span class="text-info"><b>&gt;&gt;</b></span></td>
                        <td><label>Coverage location : </label></td>
                        <td>
                            <span class="text-error size11" id="mandatory_msg">*Select coverage level first</span> 
                            <span id="coverage_location_content"></span>
                        </td>
                    </tr>
                     <tr>
                        <td><label>Longitude : </label></td>
                        <td> <input type="text" name="longitude" placeholder="Enter Longitude"/></td>
                        <td style="width:50px" ></td>
                        <td><label>Latitude : </label></td>
                        <td> <input type="text" name="latitude" placeholder="Enter Latitude"/></td>
                        
                    </tr>
                    <tr>
                        <td style="vertical-align: top">
                            <label for="main-organizer-block">Main organizer : </label>
                           <!-- <input type="radio" checked="checked" id="main-organizer-radio" name="organizer-radio">&nbsp; <label for="main-organizer-radio" style="display:inline-block"><span class="text-warning"> select </span></label> -->
                        </td>
                        <td>
                            <input type="hidden" name="org_identifier" id="org_identifier" />
                            <div id="main-organizer-block">
                                <?php
                                if (isset($organizer_array)) {
                                    for ($i = 0; $i < count($organizer_array); $i++) {
                                        if ($i != 0) {
                                            echo '<br />';
                                        }
                                        echo '<input type="checkbox" class="tg" id="mainorg_' . $organizer_array[$i][0] . '" name="mainorg_' . $organizer_array[$i][0] . '" value="' . $organizer_array[$i][1] . '" /> &nbsp; ' . $organizer_array[$i][1];
                                    }
                                } else {
                                    echo "<div class='message-error'><p class='text-error'> Some error occured!</p>
                                          <p class='text-error'><a href='../Home/newevents'>Click here</a> to retry</p></div>";
                                }
                                ?>
                            </div>
                        </td>
                        <td style="width:50px" ></td>
                        <td style="vertical-align: top">
                            <label for="implementing-partner-block">Implementing partner : </label>
                      <!--     <input type="radio" id="implementing-partner-radio" name="organizer-radio" />&nbsp;<label for="implementing-partner-radio" style="display:inline-block"><span class="text-warning"> select </span></label> -->
                        </td>
                        <td>
                            <div id="implementing-partner-block">
                                <?php
                                if (isset($organizer_array)) {
                                    for ($i = 0; $i < count($organizer_array); $i++) {
                                        if ($i != 0) {
                                            echo '<br />';
                                        }
                                        echo '<input type="checkbox" class="tg" id="implpartner_' . $organizer_array[$i][0] . '"  name="implpartner_' . $organizer_array[$i][0] . '" value="' . $organizer_array[$i][1] . '" /> &nbsp; ' . $organizer_array[$i][1];
                                    }
                                } else {
                                    echo "<div class='message-error'><p class='text-error'> Some error occured!</p>
                                          <p class='text-error'><a href='../HomeController/events'>Click here</a> to retry</p></div>";
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Venue : </label></td>
                        <td>
                            <input type="text" name="event_venue" placeholder="Enter venue"/>
                        </td>
                        <td style="width:50px" ></td>
                        <td><label>Address : </label></td>
                        <td>
                            <input type="text" name="event_address" placeholder="Enter address"/>
                        </td>
                    </tr>
                   
                    <tr>
                        <td colspan="5" style="padding:20px 0 0 0">
                            <button id="save_event_btn"  class="btn btn-info">Save event</button>
                            <input type="reset" class="btn" value="Reset"/>
                        </td>
                    </tr>
                </table>
                <?php echo form_close(); ?>
            </td>
        </tr>
    </table>
    <p class="text-info">
        <i>   * The page will be redirected to participants page after saving this event.</i>
    </p>
</div> <!-- end of container tag-->



<div id="dialog" style="width:auto" title="" style="padding:10px;display:none">

</div>