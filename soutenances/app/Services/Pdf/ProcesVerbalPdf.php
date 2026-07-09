<?php

namespace App\Services\Pdf;

use App\Models\Professeur;
use App\Models\Soutenance;

/**
 * Génère le "Procès verbal de soutenance" au format défini dans Projet 2.pdf.
 *
 * Nécessite : composer require setasign/fpdf
 * La classe \FPDF fournie par ce package n'est PAS namespacée (classe globale),
 * d'où le "extends \FPDF" ci-dessous.
 */
class ProcesVerbalPdf extends \FPDF
{
    /**
     * FPDF (dans sa version de base, sans tFPDF) n'accepte pas l'UTF-8.
     * On convertit donc chaque texte avant de l'écrire dans le PDF.
     */
    protected function t(string $text): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text) ?: $text;
    }

    /**
     * Convertit une note de 0 à 20 en toutes lettres (français).
     */
    protected function noteEnLettres(int $note): string
    {
        $mots = [
            0 => 'zéro', 1 => 'un', 2 => 'deux', 3 => 'trois', 4 => 'quatre',
            5 => 'cinq', 6 => 'six', 7 => 'sept', 8 => 'huit', 9 => 'neuf',
            10 => 'dix', 11 => 'onze', 12 => 'douze', 13 => 'treize', 14 => 'quatorze',
            15 => 'quinze', 16 => 'seize', 17 => 'dix-sept', 18 => 'dix-huit',
            19 => 'dix-neuf', 20 => 'vingt',
        ];

        return $mots[$note] ?? (string) $note;
    }

    /**
     * Déduit l'intitulé du diplôme à partir du niveau de l'étudiant.
     * NB : à ajuster si votre établissement utilise d'autres intitulés.
     */
    protected function diplome(?string $niveau): string
    {
        return match ($niveau) {
            'L3' => 'Licence Professionnelle',
            'M1' => 'Maîtrise',
            'M2' => 'Master',
            default => 'Licence Professionnelle',
        };
    }

    /**
     * Traduit le code de parcours (GB, SR, IG) en libellé complet.
     * NB : libellés à confirmer/ajuster selon votre nomenclature réelle.
     */
    protected function parcoursLabel(?string $code): string
    {
        return match ($code) {
            'GB' => 'Génie logiciel et Gestion des Bases de données',
            'SR' => 'Administration des Systèmes et Réseaux',
            'IG' => 'Informatique Générale',
            default => $code ?? '',
        };
    }

    public function buildDocument(Soutenance $soutenance): void
    {
        $etudiant      = $soutenance->etudiant;
        $organisme     = $soutenance->organisme;
        $president     = $soutenance->presidentProf;
        $examinateur   = $soutenance->examinateurProf;
        $rapporteurInt = $soutenance->rapporteurInt;
        $rapporteurExt = $soutenance->rapporteurExt;

        $this->SetMargins(25, 20, 25);
        $this->SetAutoPageBreak(true, 20);
        $this->AddPage();

        // ---- Titre ----
        $this->SetFont('Times', 'B', 16);
        $this->Cell(0, 10, $this->t('PROCES VERBAL'), 0, 1, 'C');

        $this->SetFont('Times', 'B', 12);
        $this->MultiCell(0, 7, $this->t(
            "SOUTENANCE DE FIN D'ETUDES POUR L'OBTENTION DU DIPLOME DE "
            . mb_strtoupper($this->diplome($etudiant->niveau ?? null))
        ), 0, 'C');

        $this->Ln(4);

        // ---- Mention / Parcours ----
        // "Mention" et "Parcours" en gras, le reste en normal, ligne centrée
        $this->centeredLabel('Mention', ' : Informatique');
        $this->centeredLabel('Parcours', ' : ' . $this->parcoursLabel($etudiant->parcours ?? null));

        $this->Ln(4);

        // ---- Identité de l'étudiant ----
        $nomComplet = strtoupper($etudiant->nom ?? '') . ' ' . ($etudiant->prenoms ?? '');
        $this->SetFont('Times', 'B', 13);
        $this->Cell(0, 8, $this->t('Mr/Mlle ' . trim($nomComplet)), 0, 1, 'C');

        $this->SetFont('Times', '', 12);
        $this->MultiCell(0, 7, $this->t(
            "a soutenu publiquement son mémoire de fin d'études pour l'obtention du diplôme de "
            . $this->diplome($etudiant->niveau ?? null)
        ), 0, 'C');

        $this->Ln(8);

        // ---- Note ----
        // Dans le modèle, seul le chiffre de la note est en gras ("14"/20 (quatorze sur vingt))
        $note = (int) $soutenance->note;
        $this->SetFont('Times', '', 12);
        $this->Write(7, $this->t(
            "Après la délibération, la commission des membres du Jury a attribué la note de "
        ));
        $this->SetFont('Times', 'B', 12);
        $this->Write(7, $this->t((string) $note));
        $this->SetFont('Times', '', 12);
        $this->Write(7, $this->t('/20 (' . $this->noteEnLettres($note) . ' sur vingt)'));
        $this->Ln(10);

        // ---- Membres du Jury ----
        $this->SetFont('Times', 'U', 12);
        $this->Cell(0, 8, $this->t('Membres du Jury'), 0, 1, 'L');
        $this->SetFont('Times', '', 12);

        $this->ligneJury('Président', $president);
        $this->ligneJury('Examinateur', $examinateur);
        $this->ligneRapporteurs($rapporteurInt, $rapporteurExt);

        // ---- Informations administratives complémentaires ----
        // (Absentes du modèle papier fourni mais utiles / déjà en base : à retirer si non souhaité)
        $this->Ln(14);
        $this->SetFont('Times', 'I', 10);
        if ($organisme) {
            $this->Cell(0, 6, $this->t("Organisme d'accueil : " . $organisme->design), 0, 1, 'L');
        }
        $this->Cell(0, 6, $this->t('Année universitaire : ' . $soutenance->annee_univ), 0, 1, 'L');

        if ($soutenance->date_soutenance) {
            $lieu = $organisme->lieu ?? '';
            $date = $soutenance->date_soutenance->format('d/m/Y');
            $this->Cell(0, 6, $this->t('Fait à ' . $lieu . ', le ' . $date), 0, 1, 'L');
        }
    }

    /**
     * Écrit une ligne centrée où $label est en gras et $suite en normal
     * (ex: "Mention" en gras + " : Informatique" en normal).
     * Nécessaire car Write() ne sait écrire qu'en alignement gauche : on calcule
     * donc la largeur totale du texte pour positionner nous-mêmes le curseur au centre.
     */
    protected function centeredLabel(string $label, string $suite): void
    {
        $this->SetFont('Times', 'B', 12);
        $labelWidth = $this->GetStringWidth($this->t($label));

        $this->SetFont('Times', '', 12);
        $suiteWidth = $this->GetStringWidth($this->t($suite));

        $totalWidth = $labelWidth + $suiteWidth;
        $this->SetX(($this->GetPageWidth() - $totalWidth) / 2);

        $this->SetFont('Times', 'B', 12);
        $this->Write(8, $this->t($label));
        $this->SetFont('Times', '', 12);
        $this->Write(8, $this->t($suite));
        $this->Ln(8);
    }

    protected function ligneJury(string $role, ?Professeur $prof): void
    {
        $suite = ' : ' . trim(($prof?->civilite ?? '') . ' ' . ($prof?->nom ?? '') . ' ' . ($prof?->prenoms ?? ''));
        if (!empty($prof?->grade)) {
            $suite .= ', ' . $prof->grade;
        }

        $this->SetFont('Times', 'B', 12);
        $this->Write(7, $this->t($role));
        $this->SetFont('Times', '', 12);
        $this->Write(7, $this->t($suite));
        $this->Ln(7);
    }

    protected function ligneRapporteurs(?Professeur $int, ?Professeur $ext): void
    {
        $suite1 = ' : ' . trim(($int?->civilite ?? '') . ' ' . ($int?->nom ?? '') . ' ' . ($int?->prenoms ?? ''));
        if (!empty($int?->grade)) {
            $suite1 .= ', ' . $int->grade;
        }

        $this->SetFont('Times', 'B', 12);
        $this->Write(7, $this->t('Rapporteurs'));
        $labelWidth = $this->GetStringWidth($this->t('Rapporteurs'));
        $this->SetFont('Times', '', 12);
        $this->Write(7, $this->t($suite1));
        $this->Ln(7);

        // Deuxième rapporteur : ligne indentée sous le nom du premier, sans répéter le label
        $suite2 = trim(($ext?->civilite ?? '') . ' ' . ($ext?->nom ?? '') . ' ' . ($ext?->prenoms ?? ''));
        if (!empty($ext?->grade)) {
            $suite2 .= ', ' . $ext->grade;
        }

        $indent = $labelWidth + $this->GetStringWidth(' : ');
        $this->Cell($indent, 7, '', 0, 0);
        $this->Write(7, $this->t($suite2));
        $this->Ln(7);
    }
}