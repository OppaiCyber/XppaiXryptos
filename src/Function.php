<?php
function PumpDump($percent){
    return $percent > 0 ?  "🍀" :  "🥀";
}

function TimeNow(){
    $h = "7";
    $hm = $h * 60; 
    $ms = $hm * 60;
    return gmdate("d/m/Y g:i:s A", time()-($ms));
}

function addTail($teks){
    if ($teks > 1000000) {
        $tail = "jt IDR";
    }elseif ($priceLast > 1000) {
        $tail = "k IDR";
    }
    else{
        $tail = "IDR";
    }
    return $teks.$tail;
}

function iPrice($coin,$amount){
    if (!empty($coin)) {
        $decode = json_decode(file_get_contents("https://indodax.com/api/summaries"), true);
        $coinArr = $decode['tickers'][$coin."_idr"];
        $coinName = $coinArr['name'];
        if (empty($coinName)){
            return "<code>Sorry we didn't support your coin yet</code>"; 
        }
        $priceLast = addTail($coinArr['last']);
        $priceBuy = addTail($coinArr['buy']);
        $priceSell = addTail($coinArr['sell']);
        $volumeIDR = addTail($coinArr['vol_idr']);

        $serverTime = gmdate("d/m/Y g:i:s A", $coinArr['server_time']);

        if (!empty(is_numeric($amount))) {
            $priceLast = $priceLast * $amount;
        }
    
        $result = "Name : $coinName\nPrice : $priceLast \nBuy : $priceBuy\nSell : $priceSell\nVolume IDR : ".number_format($volumeIDR)."\n\nMarket : Indodax - $serverTime";

    return $result;
    }else{
        return "Please submit with right format\nExample : /indodax ignis\nor you can calculate too with /indodax doge 1000</code>";
    }
}

function topTen(){
    $decode = json_decode(file_get_contents("https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd"), true);
    $result = "Top 10 Marketcap\n";
    for ($i=0; $i < 10; $i++) { 
        $coinname = $decode[$i]['name'];
        $coinSymbol = $decode[$i]['symbol'];
        $price = $decode[$i]['current_price'];
        $changeday = $decode[$i]['market_cap_change_percentage_24h'];
        $UpDownIcon = PumpDump($changeday);
        $a = $i+1;
        $result .= "$a. $coinname ($coinSymbol): $price USD $UpDownIcon $changeday \n";
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
        $coinArr = $decode["RAW"][$coinx];
        $priceBTC = $coinArr["BTC"]['PRICE'];
        $priceUSD = $coinArr["USD"]['PRICE'];
        $priceIDR = $coinArr["IDR"]['PRICE'];

        $formulaBTC = $priceBTC * $amount;
        $formulaUSD = $priceUSD * $amount;
        $formulaIDR = addTail($priceIDR * $amount);

    $result  = "<code>💎Xryptos Calculator $amount $coinx : \n🕓".TimeNow();
    $result .= "\n₿ $formulaBTC BTC\n$ $formulaUSD USD\nRp. ".number_format($formulaIDR,2,',','.')."</code>";
    return $result;
    }
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
        $formula = addTail($idr['PRICE']);
        $changesIDR = $idr['CHANGEPCTDAY'];
        $lowDayIDR = addTail($idr['LOWDAY']);
        $highDayIDR = addTail($idr['HIGHDAY']);

    $result  = "<code>💎Stats $coinx : \n";
    $result .= PumpDump($changesUSD).number_format($changesUSD,2)."% | $ $priceUSD \n".PumpDump($changesIDR).number_format($changesIDR,2)."% | ".number_format($formula,2,',','.')." \nHigh : ".number_format($highDayIDR,2,',','.')." | $ $highDayUSD \nLow : ".number_format($lowDayIDR,2,',','.')." | $ $lowDayUSD</code>";
    return $result;
    }
}

function AssetCalculator($amount, $pair1, $pair2){
    $pair1x = strtoupper($pair1);
    $pair2x = strtoupper($pair2);
    $getData = json_decode(file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=$pair1x&tsyms=$pair2x"), true);
    $IDR = $getData[''.$pair2x.''];
    $formula = addTail($IDR * $amount);
    $text = "💎Asset Calculator \n$amount $pair1x = $formula $pair2x";
        if ($pair2 == "idr") {
            $idr_result = "Rp " . number_format($formula,2,',','.');
            $text = "💎Asset Calculator \n$amount $pair1x = $idr_result";
        }elseif($formula == ""){
            $text = "<code>Sorry we didn't support your coin yet\nPlease submit with right format\nExample : ex 12 eth idr</code>";
        }
    return $text;
}

function GlobalStat(){
    $main = json_decode(file_get_contents("https://api.coinlore.com/api/global/"),true);
    $firstMain = $main[0];
    $totalCoin = $firstMain['coins_count'];
    $activeMarket = $firstMain['active_markets'];
    $totalMcap = number_format($firstMain['total_mcap']);
    $totalVolume = number_format($firstMain['total_volume']);

    $btcValue = $firstMain['btc_d'];
    $ethValue = $firstMain['eth_d'];
    $mcapChange = $firstMain['mcap_change'];

    $volumeChange = $firstMain['volume_change'];
    $avgChange = $firstMain['avg_change_percent'];
    $volumeAth = $firstMain['volume_ath'];
    $mcapAth = number_format($firstMain['mcap_ath']);

    $volChangeIcon = PumpDump($volumeChange);
    $avgChangeIcon = PumpDump($avgChange);
    $mcapChangeIcon = PumpDump($mcapChange);

    $result = "Global Cryptocurrency Stats\nTotal Coin : 💱 $totalCoin\nActive Market : 🛒 $activeMarket\nValue in BTC : ₿ $btcValue BTC\nValue in ETH : 💎 $ethValue ETH\nVolume Change : $volChangeIcon $volumeChange %\nAverage Change : $avgChangeIcon $avgChange %\nMarketcap Change : $mcapChangeIcon $mcapChange %\nTotal Volume : 💸 $totalVolume USD\nTotal Marketcap : 💸 $totalMcap USD\nMarketcap AllDayHigh : 💸 $mcapAth USD\n";
    return $result;
}
