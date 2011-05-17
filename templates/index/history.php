<article class="grid_12 content article">
	<header>
		<h1><?php echo $this->translate->_('recentChanges') ?></h1>
	</header>
	<section class="grid_12">
		<ol class="latest-changes">
			<?php foreach($this->latestChanges as $changeGroup => $changes) : ?>
			<ol class="timespan <?php echo $changeGroup; ?>">
				<?php foreach($changes as $change) : ?>
				<?php $user = $change->getUser(); ?>
				<li class="version">
					<span class="timestamp">
						<span class="time"><?php echo date('H:i', intval($change->timestamp)); ?></span>,
						<span class="date"><?php echo date('d.m.Y', intval($change->timestamp)); ?></span>
					</span>
					<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email); ?>?s=16&d=mm" class="avatar" />
					<span class="author"><?php echo $user->name; ?></span>
					<span class="comment">&quot;<?php echo $change->versionComment; ?>&quot;</span>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endforeach; ?>
		</ol>
	</section>
</article>
