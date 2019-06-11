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

function priceChecker($coin){
    $coinx = strtoupper($coin);
    $getData = seeURL("https://min-api.cryptocompare.com/data/pricemultifull?fsyms=$coinx&tsyms=BTC,USD,IDR");
    $decode = json_decode($getData,TRUE);
    @$error = $decode['HasWarning'];

    if ($error == 1) {
    	return "❌ This coin is not listed on exchanges we support ❌";
    }else{
	    $usd = $decode["RAW"][$coinx]["USD"];
	    $priceUSD = $usd['PRICE'];
	    $changesUSD = $usd['CHANGEPCTDAY'];
	    $lowDayUSD = $usd['LOWDAY'];
	    $highDayUSD = $usd['HIGHDAY'];

	    $idr = $decode["RAW"][$coinx]["IDR"];
	    $priceIDR = $idr['PRICE'];
	    $changesIDR = $idr['CHANGEPCTDAY'];
	    $lowDayIDR = $idr['LOWDAY'];
	    $highDayIDR = $idr['HIGHDAY'];

	    if ($priceIDR > 1000000) {
	        $priceIDR = $priceIDR / 1000000;
	        $lowDayIDR = $lowDayIDR / 1000000;
	        $highDayIDR = $highDayIDR / 1000000;
	        $tail = "jt IDR";
	    }else{
	        $tail = "IDR";
	    }

	$result  = "<code>Stats $coinx : \n";
    $result .= PumpDump($changesUSD).number_format($changesUSD,2)."% | $ $priceUSD \n".PumpDump($changesIDR).number_format($changesIDR,2)."% | ".number_format($priceIDR,2,',','.')." $tail \nHigh : ".number_format($highDayIDR,2,',','.')." $tail | $ $highDayUSD \nLow : ".number_format($lowDayIDR,2,',','.')." $tail | $ $lowDayUSD</code>";
    return $result;
    }// end of else

    
} // end func

?>
