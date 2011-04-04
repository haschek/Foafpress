<?php

echo '<h2>About me</h2>'.PHP_EOL;

// foaf:interest

if (count($interests) > 0)
{
    echo '<h3>Interests</h3>'.PHP_EOL;
    echo '<ul class="inline">'.PHP_EOL;
    foreach ($interests as $interest)
    {
        if (count($interest) > 1 && isset($interest['description']))
        {
            echo '<li class="inline"><a href="#'.md5($interest['label']).'">'.$interest['label'].'</a></li>'.PHP_EOL;
        }
        else
        {
            echo '<li class="inline">'.$interest['label'].'</li>'.PHP_EOL;
        }

        unset($interest);
    }
    echo '</ul>'.PHP_EOL;
    unset($interests);
}

// foaf:[current|past]Project

if (count($projects) > 0)
{
    echo '<h3>Projects</h3>'.PHP_EOL;
    echo '<ul class="inline">'.PHP_EOL;
    foreach ($projects as $project)
    {
        if (count($project) > 1 && isset($project['description']))
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
