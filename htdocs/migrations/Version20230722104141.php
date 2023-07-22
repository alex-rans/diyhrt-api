<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230722104141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("InHousePharmacy", "Ships from Vanuatu", "a:1:{i:0;s:13:\"Bank Transfer\";}", "https://inhousepharmacy.vu")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("EUAibolit", "Ships from EU", "a:1:{i:0;s:13:\"Bank Transfer\";}", "https://diyhrt.cafe/index.php/EUAibolit_Updates")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("UnitedPharmacies-UK", "HK to UK", "a:2:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";}", "https://unitedpharmacies-uk.md/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("UnitedPharmacies-US", "HK to US", "a:2:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";}", "https://https://unitedpharmacies.md/.vu")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("AllDayChemist", "Ships from India", "a:2:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";}", "https://alldaychemist.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("ReliableRxPharmacy", "Ships from India", "a:2:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";}", "https://www.reliablerxpharmacy.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Shape Shifter", "Ships from Turkey", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://sshifter.puzl.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("RxAisle", "Ships from Turkey", "a:4:{i:0;s:13:\"Bank Transfer\";i:1;s:4:\"Wise\";i:2;s:11:\"Credit Card\";i:3;s:7:\"Bitcoin\";}", "https://rxaisle.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Pilloid", "Ships from Russia", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:6:\"PayPal\";i:2;s:7:\"Bitcoin\";}", "https://pilloid.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("OTC-Online-Store ", "Ships from Russia", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://otc-online-store.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("GoodStuffStore", "Ships from Thailand", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";i:2;s:9:\"Moneygram\";}", "https://www.goodstuffstore.net/store/Hormone-Therapy-c43286627")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Favskinhouse", "Ships from Thailand", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:4:\"Wise\";i:2;s:9:\"Moneygram\";}", "https://favskinhouse.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Amazing4Health", "Ships from Thailand", "a:4:{i:0;s:13:\"Bank Transfer\";i:1;s:4:\"Wise\";i:2;s:11:\"Credit Card\";i:3;s:6:\"PayPal\";}", "https://amazing4health.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("OtokonokoPharma", "Ships from Brazil", "a:1:{i:0;s:7:\"Bitcoin\";}", "http://otkph.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("PharmaOnline", "Ships from EU", "a:1:{i:0;s:13:\"Bank Transfer\";}", "https://pharmaonline.tv/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("WebOrderPharmacy-US", "India to US", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://www.weborderpharmacy.md/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("WebOrderPharmacy-UK", "India to UK", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://www.weborderpharmacy-uk.md/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Medicina Mexico ", "Ships from Mexico", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://www.meds.com.mx/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Bio-Japan", "Ships from Japan", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:4:\"Wise\";i:2;s:7:\"Bitcoin\";}", "https://bio-japan.net/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("BG Pharmacy", "Ships from EU+", "a:2:{i:0;s:13:\"Bank Transfer\";i:1;s:7:\"Bitcoin\";}", "https://www.onlinepharmacy-bg.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Farmacy Houses", "Ships from Russia", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:4:\"Wise\";i:2;s:7:\"Bitcoin\";}", "https://farmacy-houses.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("ZeemoreUncle", "Ships from Thailand", "a:3:{i:0;s:13:\"Bank Transfer\";i:1;s:9:\"Moneygram\";i:2;s:7:\"Bitcoin\";}", "https://www.zeemoreuncle.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("DragonOrdnance", "Ships from Turkey, China or US", "a:2:{i:0;s:4:\"Wise\";i:1;s:7:\"Bitcoin\";}", "https://www.dragonordnance.com/category?tid=13")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("DashPCT", "Ships from India/SG", "a:2:{i:0;s:4:\"Wise\";i:1;s:7:\"Bitcoin\";}", "https://dashpct.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("OELabs", "Ships from EU", "a:1:{i:0;s:7:\"Bitcoin\";}  ", "https://shop.oelabs.co/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("FOLX Health", "Ships to US only", "a:1:{i:0;s:11:\"Credit Card\";}", "https://www.folxhealth.com/product/estrogen/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Plume", "Ships to US only", "a:1:{i:0;s:11:\"Credit Card\";}", "https://getplume.co/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Vanna Pharma", "UK to UK", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://vannapharma.com/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Ru-Pills", "Ships from Russia", "a:2:{i:0;s:7:\"Bitcoin\";i:1;s:11:\"Credit Card\";}", "https://ru-pills.com/hrt-cafe/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("AstroVials", "Ships worldwide from EU", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://astrovials.co/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Felicitas", "Ships from Germany to EU", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://www.flcts.eu/")');
        $this->addSql('INSERT INTO supplier (name, shipping, payment_methods, url) VALUES ("Girlpotion", "Ships from EU to EU", "a:1:{i:0;s:7:\"Bitcoin\";}", "https://girlpotion.com/")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE supplier');
    }
}
