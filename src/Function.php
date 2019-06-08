<?php

function seeURL($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($ch);
}

function IDRFormula($num){
	return number_format($num,2,',','.');
}

function PumpDump($percent){
	if ($percent > 0) {return "🍀";}else{return "🥀";}
}

function percentChange($last,$day){
	$increase = $last - $day;
	$percent = $increase / $day * 100;
	return round($percent,2)."%";
}

function gasChecker(){
    $getData = json_decode(seeURL("https://ethgasstation.info/json/ethgasAPI.json"),true);
    $safeLow = $getData['safeLow'] / 10;
    $fastest = $getData['fastest'] / 10;
    $medium = $getData['average'] / 10;
    $fast = $getData['fast'] / 10;
    $result = "💎Ethereum Gas Prices\n<code>🚲SafeLow -> $safeLow Gwei\n🚗Medium -> $medium Gwei\n🚄Fast -> $fast Gwei\n🚀Fastest -> $fastest Gwei</code>";
    return $result;
}

function IndodaxPrices(){
	$decode = json_decode(seeURL("https://indodax.com/api/summaries"), true);
	$array = array('btc_idr','ten_idr','abyss_idr','act_idr','ada_idr','aoa_idr','bcd_idr','bchabc_idr','bchsv_idr','bnb_idr','btg_idr','bts_idr','btt_idr','cro_idr','drk_idr','dax_idr','doge_idr','eth_idr','eos_idr','etc_idr','gsc_idr','gxs_idr','hpb_idr','ignis_idr','inx_idr','ltc_idr','neo_idr','npxs_idr','nxt_idr','ont_idr','pxg_idr','qtum_idr','rvn_idr','scc_idr','stq_idr','sumo_idr','trx_idr','usdc_idr','usdt_idr','vex_idr','waves_idr','str_idr','nem_idr','xdce_idr','xrp_idr','xzc_idr','bts_btc','drk_btc','doge_btc','eth_btc','ltc_btc','nxt_btc','sumo_btc','ten_btc','nem_btc','str_btc','xrp_btc');

	$arrayChange = array('btcidr','tenidr','abyssidr','actidr','adaidr','aoaidr','bcdidr','bchabcidr','bchsvidr','bnbidr','btgidr','btsidr','bttidr','croidr','drkidr','daxidr','dogeidr','ethidr','eosidr','etcidr','gscidr','gxsidr','hpbidr','ignisidr','inxidr','ltcidr','neoidr','npxsidr','nxtidr','ontidr','pxgidr','qtumidr','rvnidr','sccidr','stqidr','sumoidr','trxidr','usdcidr','usdtidr','vexidr','wavesidr','stridr','nemidr','xdceidr','xrpidr','xzcidr','btsbtc','drkbtc','dogebtc','ethbtc','ltcbtc','nxtbtc','sumobtc','tenbtc','nembtc','strbtc','xrpbtc');



	$main = $decode['tickers'];
	$text = 'Prices List<br>';
	for ($i=0; $i < count($array); $i++) { 
			$name = $main[$array[$i]]['name'];
			$prices = $main[$array[$i]]['last'];
			$changeDay = $decode['prices_24h'][$arrayChange[$i]];
			$percentDay = percentChange($prices,$changeDay);
			$PumpDump = PumpDump($percentDay);
		if (strpos($array[$i], 'idr') != null) {$prices = IDRFormula($prices);$pair = "IDR";}else{$pair = "BTC";}
				
			$text .= "$PumpDump $percentDay Nama : $name - $prices $pair<br>";

	}

} // End Func

?>
