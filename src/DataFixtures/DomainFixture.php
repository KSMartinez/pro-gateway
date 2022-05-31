<?php

namespace App\DataFixtures;

use App\Entity\Domain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 *
 */
class DomainFixture extends Fixture
{

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $domains = [
            Domain::ART_CULTURE_AUDIOVISUEL,
            Domain::ACTIVITES_JURIDIQUES,
            Domain::ADMINISTRATIONS_EUROPEENNES_ET_INTERNATIONALES,
            Domain::BTP_GENIE_CIVIL_IMMOBILIER,
            Domain::COMMERCIAL_MARKETING,
            Domain::COMMUNICATION_MULTIMEDIA_DIGITAL_JOURNALISME,
            Domain::ENSEIGNEMENT_RECHERCHE_FORMATION,
            Domain::ETUDES_RECHERCHES_DEVELOPPEMENT_BIOLOGIE_PHYSIQUE_CHIMIE_MATHEMATIQUES,
            Domain::FINANCE_GESTION_AUDIT_BANQUE_ASSURANCE,
            Domain::FONCTION_PUBLIQUE_ETAT_TERRITORIALE_HOSPITALIERE,
            Domain::GENIE_CIVIL_GENIE_ELECTRIQUE_ENERGIE,
            Domain::INFORMATIQUE_SI_RESEAUX,
            Domain::MANAGEMENT_CONSEIL_STRATEGIE_COACHING,
            Domain::QUALITE_ENVIRONNEMENT_SECURITE,
            Domain::RESTAURATION_HOTELLERIE_TOURISME,
            Domain::RH_SOCIOLOGIE_PSYCHOLOGIE,
            Domain::SANTE_SOCIAL_MEDECINE,
            Domain::SPORT,
            Domain::TRANSPORT_LOGISTIQUE
        ];

        foreach ($domains as $domain) {

            $domainObj = new Domain();
            $domainObj->setName($domain);
            $manager->persist($domainObj);
        }


        $manager->flush();
    }
}