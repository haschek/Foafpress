<?php

echo '<h2>Persönliches</h2>'.PHP_EOL;

// foaf:interest

if (count($interests) > 1)
{
    echo '<h3>Interessen</h3>'.PHP_EOL;
    echo '<ul class="inline">'.PHP_EOL;
    foreach ($interests as $interest)
    {
        if (count($interest) > 1)
        {
            echo '<li class="inline"><a href="#'.md5($interest['label']).'">'.$interest['label'].'</a></li>'.PHP_EOL;
        }
        else
        {
            echo '<li>'.$interest['label'].'</li>'.PHP_EOL;
        }

        unset($interest);
    }
    echo '</ul>'.PHP_EOL;
    unset($interests);
}

// foaf:[current|past]Project

if (count($projects) > 1)
{
    echo '<h3>Projekte</h3>'.PHP_EOL;
    echo '<ul class="inline">'.PHP_EOL;
    foreach ($projects as $project)
    {
        if (count($project) > 1)
        {
            echo '<li class="inline"><a href="#'.md5($project['label']).'">'.$project['label'].'</a></li>'.PHP_EOL;
        }
        else
        {
            echo '<li class="inline">'.$project['label'].'</li>'.PHP_EOL;
        }

        unset($project);
    }
    echo '</ul>'.PHP_EOL;
    unset($projects);
}

// cv:hasSkill

if (count($skills) > 1)
{
    echo '<h3>Skills (Level 0..5)</h3>'.PHP_EOL;
    echo '<ul class="inline">'.PHP_EOL;
    foreach ($skills as $skill)
    {
        echo '<li class="inline">'.$skill['label'].($skill['level']?' ('.$skill['level'].')':'').'</li>'.PHP_EOL;
        unset($skill);
    }
    echo '</ul>'.PHP_EOL;
    unset($skills);
}

?>
