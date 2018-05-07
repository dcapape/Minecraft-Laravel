<div class="footer footerText container-fluid">
  <div class=" pull-right" style="margin-top: 3px;">
    <div class="input-group input-group-sm">
      {{Form::select('locale', $locales, LaravelLocalization::getCurrentLocale(), ['id' => 'locale', 'class' => 'form-control']);}}
    </div>
  </div>
  <div class="col-md-4">
    <b>minegamers.pro 2018</b>
  </div>
</div>

<script>
$("#locale").change(function () {
    var newlocale = this.value;
    var locales = new Array();
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
      locales['{{$localeCode}}'] = '{{LaravelLocalization::getLocalizedURL($localeCode) }}';
    @endforeach
    window.location.href = locales[newlocale];
 });
</script>
