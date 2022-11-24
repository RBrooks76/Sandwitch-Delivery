<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

@php
$selctor = 'ltr';
if (app()->getLocale() == 'ar') {
    $selctor = 'rtl';
}
@endphp

<!-- JQUERY JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/jquery-3.4.1.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/bootstrap/js/popper.min.js"></script>

<!-- SPARKLINE -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/jquery.sparkline.min.js"></script>

<!-- CHART-CIRCLE -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/circle-progress.min.js"></script>

<!-- RATING STAR -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/rating/jquery.rating-stars.js"></script>

<!-- C3.JS CHART PLUGIN -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/charts-c3/d3.v5.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/charts-c3/c3-chart.js"></script>

<!-- INPUT MASK PLUGIN-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/input-mask/jquery.mask.min.js"></script>

<!-- CHARTJS CHART -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/chart/Chart.bundle.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/chart/utils.js"></script>

<!-- CUSTOM SCROLLBAR -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- SIDE-MENU -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/sidemenu/sidemenu.js"></script>

<!-- PIETY CHART -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/peitychart/jquery.peity.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/peitychart/peitychart.init.js"></script>

<!-- Echarts Js-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/echarts/echarts.js"></script>

<!--MORRIS JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/morris/morris.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/morris/raphael-min.js"></script>


<!-- CUSTOM SCROLL BAR JS-->
{{-- <script src="/{{$path}}files/dash_board/{{$selctor}}/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script> --}}

<!-- SIDEBAR JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/sidebar/sidebar.js"></script>

<!-- APEXCHART JS -->
<!-- <script src="{{ $path }}files/dash_board/{{ $selctor }}/js/apexcharts.js"></script> -->

<!-- INDEX-SCRIPTS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/index5.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/index2.js"></script>

<!-- SPARKLINE JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/jquery.sparkline.min.js"></script>
<!--  -->
<!-- CHART-CIRCLE JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/circle-progress.min.js"></script>

<!-- RATING STAR JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/rating/jquery.rating-stars.js"></script>

<!-- C3 CHART JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/charts-c3/d3.v5.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/charts-c3/c3-chart.js"></script>

<!-- INPUT MASK JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/input-mask/jquery.mask.min.js"></script>

<!-- DATA TABLE JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/datatable/dataTables.bootstrap4.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/datatable/datatable.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/datatable/dataTables.responsive.min.js"></script>
<!--  -->
<!-- FORMEDITOR JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/summernote.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/formeditor.js"></script>

<!-- FILE UPLOADES JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/fileuploads/js/fileupload.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/fileuploads/js/file-upload.js"></script>

<!-- SELECT2 JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/select2/select2.full.min.js"></script>

<!-- BOOTSTRAP-DATERANGEPICKER JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- WYSIWYG Editor JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/wysiwyag/jquery.richtext.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/wysiwyag/wysiwyag.js"></script>

<!-- FORMELEMENTS JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/form-elements.js"></script>
<!-- SUMMERNOTE JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/summernote/summernote-bs4.js"></script>

<!-- MULTI SELECT JS-->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/multipleselect/multiple-select.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/multipleselect/multi-select.js"></script>

<!-- DATEPICKER JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/date-picker/spectrum.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/date-picker/jquery-ui.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/input-mask/jquery.maskedinput.js"></script>

<!-- TIMEPICKER JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/time-picker/jquery.timepicker.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/time-picker/toggles.min.js"></script>

<!-- GALLERY JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/picturefill.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lightgallery.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lightgallery-1.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-pager.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-autoplay.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-fullscreen.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-zoom.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-hash.js"></script>
<script src="{{ $path }}files/dash_board/{{ $selctor }}/plugins/gallery/lg-share.js"></script>

<!--CUSTOM JS -->
<script src="{{ $path }}files/dash_board/{{ $selctor }}/js/custom.js"></script>


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script src="{{ $path . 'js/toastr.min.js' }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-lite.min.js"></script>
<script src="{{ $path . 'nprogress-master/nprogress.js' }}"></script>
<script src="{{ $path . 'js/jquery.form.min.js' }}"></script>
<script src="{{ $path . 'js/intro.js' }}"></script>
<script src="{{ $path . 'js/master.js' }}"></script>
