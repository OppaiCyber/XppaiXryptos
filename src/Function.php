<?php

function PumpDump($percent){
    if ($percent > 0) {return "🍀";}else{return "🥀";}
}

function Indodax(){
    $yeet = file_get_contents("https://beras.me/OppaiCyber/api.php");
    return $yeet;
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
