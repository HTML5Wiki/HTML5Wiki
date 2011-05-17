<article class="grid_12 content recentchanges">
	<header>
		<h1><?php echo $this->translate->_('recentChanges') ?></h1>
	</header>
	<section>
		<ol class="changes">
			<?php foreach($this->latestChanges as $changeGroup => $changes) : ?>
			<ol class="timespan <?php echo $changeGroup; ?>">
				<?php foreach($changes as $change) : ?>
				<?php $user = $change->getUser(); ?>
				<li class="change">
					<h2 class="name">
						<a href="<?php echo $this->urlHelper('wiki', $change->permalink) ?>">
							<?php echo $change->getCommonName() ?>
						</a>
					</h2>
					<p class="meta">
						<span class="timestamp">
							<span class="time"><?php echo date('H:i', intval($change->timestamp)); ?></span>,
							<span class="date"><?php echo date('d.m.Y', intval($change->timestamp)); ?></span>
						</span>
						<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email); ?>?s=16&d=mm" class="avatar" />
						<span class="author"><?php echo $user->name; ?></span>
						<span class="comment">&quot;<?php echo $change->versionComment; ?>&quot;</span>
					</p>
				</li>
				<?php endforeach; ?>
			</ol>
			<?php endforeach; ?>
		</ol>
	</section>
</article>
