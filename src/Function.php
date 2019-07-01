<?php

function PumpDump($percent){
    return $percent > 0 ?  "🍀" :  "🥀";
}

function TimeNow(){
    $h = "7";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
    $hm = $h * 60; 
    $ms = $hm * 60;
    return gmdate("d/m/Y g:i:s A", time()-($ms)); // the "-" can be switched to a plus if that's what your time zone is.
}

function topTen(){
    $decode = json_decode(file_get_contents("https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd"), true);
    $result = "Top 10 Marketcap\n";
    $a = 1;
    for ($i=0; $i < 10; $i++) { 
        $coinname = $decode[$i]['name'];
        $coinSymbol = $decode[$i]['symbol'];
        $price = $decode[$i]['current_price'];
        $changeday = $decode[$i]['market_cap_change_percentage_24h'];
        $UpDownIcon = PumpDump($changeday);
        $result .= "$a. $coinname ($coinSymbol): $price USD $UpDownIcon $changeday \n";
        $a++;
    }
    return $result;
}

function latestNews(){
	$decode = json_decode($getData = file_get_contents("https://cryptocontrol.io/api/v1/public/news?key=2250d3851ea3eeef85bc26f4f5621775"), true);
	$news = "Latest News\n";
	for ($i=0; $i < count($decode[0]['similarArticles']); $i++) { 
		$news .= "<a href='".$decode[0]['similarArticles'][$i]['url']."'>".$decode[0]['similarArticles'][$i]['title']."</a>\n";
	}
	return $news;
}

function Calculatorv2($coin,$amount){
    $coinx = strtoupper($coin);
    $getData = file_get_contents("https://min-api.cryptocompare.com/data/pricemultifull?fsyms=$coinx&tsyms=BTC,USD,IDR");
    $decode = json_decode($getData,TRUE);
    @$error = $decode['HasWarning'];
    if (empty($coin) || $error == 1) {
        return "<code>Sorry we didn't support your coin yet\nPlease submit with right format\nExample : /calc ignis 222</code>";
    }else{

        $priceBTC = $decode["RAW"][$coinx]["BTC"]['PRICE'];
        $priceUSD = $decode["RAW"][$coinx]["USD"]['PRICE'];
        $priceIDR = $decode["RAW"][$coinx]["IDR"]['PRICE'];
        
        $formulaBTC = $priceBTC * $amount;
        $formulaUSD = $priceUSD * $amount;
        $formulaIDR = $priceIDR * $amount;

        if ($formulaIDR > 1000000) {
            $formulaIDR = $formulaIDR / 1000000;
            $tail = "jt IDR";
        }elseif ($formulaIDR > 1000) {
            $formulaIDR = $formulaIDR / 1000;
            $tail = "k IDR";
        }
        else{
            $tail = "IDR";
        }

    $result  = "<code>💎Xryptos Calculator $amount $coinx : \n🕓".TimeNow();
    $result .= "\n₿ $formulaBTC BTC\n$ $formulaUSD USD\nRp. ".number_format($formulaIDR,2,',','.')." $tail</code>";
    return $result;
    }// end of else
}

function gasChecker(){
    $getData = json_decode(file_get_contents("https://ethgasstation.info/json/ethgasAPI.json"),true);

    $safeLow = $getData['safeLow'] / 10;
    $fastest = $getData['fastest'] / 10;
    $medium = $getData['average'] / 10;
    $fast = $getData['fast'] / 10;
    $result = "💎Ethereum Gas Prices \n<code>🚲SafeLow -> $safeLow Gwei\n🚗Medium -> $medium Gwei\n🚄Fast -> $fast Gwei\n🚀Fastest -> $fastest Gwei</code>";
    return $result;
}

function priceChecker($coin){
    $coinx = strtoupper($coin);
    $getData = file_get_contents("https://min-api.cryptocompare.com/data/pricemultifull?fsyms=$coinx&tsyms=BTC,USD,IDR");
    $decode = json_decode($getData,TRUE);
    @$error = $decode['HasWarning'];
    if (empty($coin) || $error == 1) {
        return "<code>Sorry we didn't support your coin yet\nPlease submit with right format\nExample : /p btc</code>";
    }else{
        $usd = $decode["RAW"][$coinx]["USD"];
        $priceUSD = $usd['PRICE'];
        $changesUSD = $usd['CHANGEPCTDAY'];
        $lowDayUSD = $usd['LOWDAY'];
        $highDayUSD = $usd['HIGHDAY'];
        $idr = $decode["RAW"][$coinx]["IDR"];
        $formula = $idr['PRICE'];
        $changesIDR = $idr['CHANGEPCTDAY'];
        $lowDayIDR = $idr['LOWDAY'];
        $highDayIDR = $idr['HIGHDAY'];
        if ($formula > 1000000) {
            $formula = $formula / 1000000;
            $lowDayIDR = $lowDayIDR / 1000000;
            $highDayIDR = $highDayIDR / 1000000;
            $tail = "jt IDR";
        }else{
            $tail = "IDR";
        }
    $result  = "<code>💎Stats $coinx : \n";
    $result .= PumpDump($changesUSD).number_format($changesUSD,2)."% | $ $priceUSD \n".PumpDump($changesIDR).number_format($changesIDR,2)."% | ".number_format($formula,2,',','.')." $tail \nHigh : ".number_format($highDayIDR,2,',','.')." $tail | $ $highDayUSD \nLow : ".number_format($lowDayIDR,2,',','.')." $tail | $ $lowDayUSD</code>";
    return $result;
    }// end of else
    
} // end func

function AssetCalculator($amount, $pair1, $pair2){
    $pair1x = strtoupper($pair1);
    $pair2x = strtoupper($pair2);
    $getData = json_decode(file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=$pair1x&tsyms=$pair2x"), true);
    $IDR = $getData[''.$pair2x.''];
    $formula = $IDR * $amount;
    $text = "💎Asset Calculator \n$amount $pair1x = $formula $pair2x";

        if ($pair2 == "idr") {
            if ($formula > 1000000) {
            $formula = $formula / 1000000;
            $tail = "jt IDR";
        }else{
            $tail = "IDR";
        }
            $idr_result = "Rp " . number_format($formula,2,',','.');
            $text = "💎Asset Calculator \n$amount $pair1x = $idr_result $tail";
        }elseif ($formula == ""){
            $text = "<code>Sorry we didn't support your coin yet\nPlease submit with right format\nExample : ex 12 eth idr</code>";
        }
    return $text;
}

function GlobalStat(){
    $main = json_decode(file_get_contents("https://api.coinlore.com/api/global/"),true);
        $totalCoin = $main[0]['coins_count'];
        $activeMarket = $main[0]['active_markets'];
        $totalMcap = number_format($main[0]['total_mcap']);
        $totalVolume = number_format($main[0]['total_volume']);
            $btcValue = $main[0]['btc_d'];
            $ethValue = $main[0]['eth_d'];
            $mcapChange = $main[0]['mcap_change'];
                $volumeChange = $main[0]['volume_change'];
                $avgChange = $main[0]['avg_change_percent'];
                $volumeAth = $main[0]['volume_ath'];
                $mcapAth = number_format($main[0]['mcap_ath']);
                    $volChangeIcon = PumpDump($volumeChange);
                    $avgChangeIcon = PumpDump($avgChange);
                    $mcapChangeIcon = PumpDump($mcapChange);


        $result = "Global Cryptocurrency Stats\nTotal Coin : 💱 $totalCoin\nActive Market : 🛒 $activeMarket\nValue in BTC : ₿ $btcValue BTC\nValue in ETH : 💎 $ethValue ETH\nVolume Change : $volChangeIcon $volumeChange %\nAverage Change : $avgChangeIcon $avgChange %\nMarketcap Change : $mcapChangeIcon $mcapChange %\nTotal Volume : 💸 $totalVolume USD\nTotal Marketcap : 💸 $totalMcap USD\nMarketcap AllDayHigh : 💸 $mcapAth USD\n";

    return $result;
}
