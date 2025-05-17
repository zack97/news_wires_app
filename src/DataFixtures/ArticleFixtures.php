<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitleArt("Article $i")
                    ->setContentArt("Contenu de l'article $i")
                    ->setImageArt("image$i.jpg")
                    ->setDateArt(new \DateTime("-$i days"))
                    ;

            $manager->persist($article);
        }

        $manager->flush();
    }
}
