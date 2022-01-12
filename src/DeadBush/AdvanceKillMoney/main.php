<?php

declare(strict_types=1);

namespace DeadBush\AdvanceKillMoney;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\level\Level;
use pocketmine\world\World;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class main extends PluginBase implements Listener{
    public function onEnable() : void{
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerDeath(PlayerDeathEvent $event) : void{
        $victim = $event->getPlayer();
        $levelName = $event->getPlayer()->getWorld()->getFolderName();
        if(in_array($levelName, $this->getConfig()->get("worlds"))){
            if($victim->getLastDamageCause() instanceof EntityDamageByEntityEvent){
                $killer = $victim->getLastDamageCause()->getDamager();
                if($this->getConfig()->get("reduceMoney") === "activate"){
                    if($this->getConfig()->get("custom") === "activate"){
                        $random = random_int(1, $this->getConfig()->get("randomise"));
                        $raw_money = EconomyAPI::getInstance()->myMoney($victim);
                        $divide_money = $raw_money / 100;
                        $money = $divide_money * $random;
                        EconomyAPI::getInstance()->addMoney($killer, $money);
                        EconomyAPI::getInstance()->reduceMoney($victim, $money);
                        $killer->sendMessage("§l§6KM §r§e>> §fYou got " . $money . "$ for killing");
                        $victim->sendMessage("§l§6KM §r§e>> §fYou lost " . $money . "$");
                    }elseif($this->getConfig()->get("custom") === "deactivate"){
                        $money = EconomyAPI::getInstance()->myMoney($victim);
                        $random = random_int(1, intval($money));
                        EconomyAPI::getInstance()->addMoney($killer, $random);
                        EconomyAPI::getInstance()->reduceMoney($victim, $random);
                        $killer->sendMessage("§l§6KM §r§e>> §fYou got " . $random . "$ for killing");
                        $victim->sendMessage("§l§6KM §r§e>> §fYou lost " . $random . "$");
                    }
                }elseif($this->getConfig()->get("reduceMoney") === "deactivate"){
                    if($this->getConfig()->get("custom") === "activate"){
                        $random = random_int(1, $this->getConfig()->get("randomise"));
                        $raw_money = EconomyAPI::getInstance()->myMoney($victim);
                        $divide_money = $raw_money / 100;
                        $money = $divide_money * $random;
                        EconomyAPI::getInstance()->addMoney($killer, $money);
                        $killer->sendMessage("§l§6KM §r§e>> §fYou got " . $money . "$ for killing");
                    }elseif($this->getConfig()->get("custom") === "deactivate"){
                        $money = EconomyAPI::getInstance()->myMoney($victim);
                        $random = random_int(1, intval($money));
                        EconomyAPI::getInstance()->addMoney($killer, $random);
                        $killer->sendMessage("§l§6KM §r§e>> §fYou got " . $random . "$ for killing");
                    }
                }
            }
        }
    }
}
