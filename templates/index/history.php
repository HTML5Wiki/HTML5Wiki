<section class="grid_12 content recentchanges">
	<header class="title">
		<h1><?php echo $this->translate->_('recentChanges') ?></h1>
	</header>
	<ol class="changes">
		<?php foreach($this->latestChanges as $changeGroup => $changes) : ?>
		<ol class="timespan <?php echo $changeGroup; ?>">
			<?php foreach($changes as $change) : ?>
			<?php $user = $change->getUser(); ?>
			<li class="change mediatype-<?php echo strtolower($change->mediaVersionType) ?>">
				<h2 class="name">
					<a href="<?php echo $this->urlHelper('wiki', $change->permalink) ?>">
						<?php echo $this->escape($change->getCommonName()) ?>
					</a>
				</h2>
				<p class="meta">
					<span class="timestamp">
						<span class="time"><?php echo date($this->translate->_('timeFormat'), intval($change->timestamp)); ?></span>,
						<span class="date"><?php echo date($this->translate->_('dateFormat'), intval($change->timestamp)); ?></span>
					</span>
					<img src="http://www.gravatar.com/avatar/<?php echo md5($user->email); ?>?s=16&d=mm" class="avatar" />
					<span class="author"><?php echo $user->name; ?></span>
					<?php if(strlen($change->versionComment) > 0) :?>
					<span class="comment">&quot;<?php echo $this->escape($change->versionComment); ?>&quot;</span>
					<?php endif; ?>
				</p>
			</li>
			<?php endforeach; ?>
		</ol>
		<?php endforeach; ?>
	</ol>
</section>
