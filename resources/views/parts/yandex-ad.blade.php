@if($locale['yandex-ad'])
<!-- Yandex.RTB R-A-{{ $locale['yandex-ad'] }}-1 -->
<div class="yandex-ad" id="yandex_rtb_R-A-{{ $locale['yandex-ad'] }}-1"></div>
<script>window.yaContextCb.push(()=>{
	Ya.Context.AdvManager.render({
		"blockId": "R-A-{{ $locale['yandex-ad'] }}-1",
		"renderTo": "yandex_rtb_R-A-{{ $locale['yandex-ad'] }}-1"
	})
})
</script>
@endif