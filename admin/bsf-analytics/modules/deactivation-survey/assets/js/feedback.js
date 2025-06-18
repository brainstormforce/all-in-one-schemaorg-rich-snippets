( function ( $ ) {
	const UserDeactivationPopup = {
		slug: '',
		skipButton: '',
		formWrapper: '',
		radioButton: '',
		closeButton: '',
		buttonAction: '',
		feedbackForm: '',
		feedbackInput: '',
		deactivateUrl: '',
		buttonTrigger: '',
		deactivateButton: '',
		submitDeactivate: '',

		/**
		 * Caches elements for later use.
		 */
		_cacheElements() {
			this.slug = udsVars?._plugin_slug || '';
			this.skipButton = $( '.uds-feedback-skip' );
			this.submitDeactivate = $( '.uds-feedback-submit' );
			this.deactivateButton = $( '#the-list' ).find(
				`.row-actions span.deactivate a`
			);
			this.feedbackForm = $( '.uds-feedback-form' ); // Feedback Form.
			this.feedbackInput = $( '.uds-options-feedback' ); // Feedback Textarea.
			this.formWrapper = $( '.uds-feedback-form--wrapper' );
			this.closeButton = $( '.uds-feedback-form--wrapper .uds-close' );
			this.radioButton = $( '.uds-reason-input' );
		},

		/**
		 * Shows the feedback popup by adding the 'show' class to the form wrapper.
		 *
		 * @param {string} slug - The slug of the plugin.
		 */
		_showPopup( slug ) {
			$( `#deactivation-survey-${ slug }` ).addClass( 'show_popup' );
		},

		/**
		 * Hides the feedback popup by removing the 'show' class from the form wrapper.
		 */
		_hidePopup() {
			this.formWrapper.removeClass( 'show_popup' );
		},

		/**
		 * Redirects to the deactivate URL if it exists, otherwise reloads the current page.
		 */
		_redirectOrReload() {
			if ( this.deactivateUrl ) {
				window.location.href = this.deactivateUrl;
			} else {
				location.reload();
			}
		},

		/**
		 * Toggles the visibility of the feedback form and CTA based on the event target's attributes.
		 *
		 * @param {Event} event - The event that triggered this function.
		 */
		_hideShowFeedbackAndCTA( event ) {
			const acceptFeedback =
				$( event.target ).attr( 'data-accept_feedback' ) === 'true';
			const showCta =
				$( event.target ).attr( 'data-show_cta' ) === 'true';

			$( event.target )
				.closest( this.formWrapper )
				.find( '.uds-options-feedback' )
				.removeClass( 'hide' )
				.addClass( acceptFeedback ? 'show' : 'hide' );
			$( event.target )
				.closest( this.formWrapper )
				.find( '.uds-option-feedback-cta' )
				.removeClass( 'hide' )
				.addClass( showCta ? 'show' : 'hide' );
		},

		/**
		 * Changes the placeholder text of the feedback input based on the event target's attribute.
		 *
		 * @param {Event} event - The event that triggered this function.
		 */
		_changePlaceholderText( event ) {
			const radioButtonPlaceholder =
				event.target.getAttribute( 'data-placeholder' );

			$( event.target )
				.closest( this.formWrapper )
				.find( this.feedbackInput )
				.attr( 'placeholder', radioButtonPlaceholder || '' );

			this._hideShowFeedbackAndCTA( event );
		},

		/**
		 * Submits the feedback form and handles the response.
		 *
		 * @param {Event}  event - The event that triggered this function.
		 * @param {Object} self  - A reference to the current object.
		 */
		_submitFeedback( event, self ) {
			event.preventDefault();

			const currentForm = $( event.target );
			const closestForm = currentForm.closest( this.feedbackForm ); // Cache the closest form

			// Gather form data.
			const formData = {
				action: 'uds_plugin_deactivate_feedback',
				security: udsVars?._ajax_nonce || '',
				reason:
					closestForm
						.find( this.radioButton.filter( ':checked' ) )
						.val() || '', // Get the selected radio button value from the current form.
				source: closestForm.find( 'input[name="source"]' ).val() || '',
				referer:
					closestForm.find( 'input[name="referer"]' ).val() || '',
				version:
					closestForm.find( 'input[name="version"]' ).val() || '',
				feedback: closestForm.find( this.feedbackInput ).val() || '', // Get the feedback input value from the current form.
			};

			currentForm
				.find( '.uds-feedback-' + this.buttonAction )
				.text( 'Deactivating.' )
				.addClass( 'processing' );

			// Prepare AJAX call.
			$.ajax( {
				url: udsVars?.ajaxurl, // URL to send the request to.
				type: 'POST', // HTTP method.
				data: formData, // Data to be sent.
				success( response ) {
					if ( response.success ) {
						self._redirectOrReload();
					}
					self._hidePopup();
				},
				/* eslint-disable */
				error( xhr, status, error ) {
					/* eslint-disable */
					self._redirectOrReload();
				},
			} );
		},

		_handleClick( e ) {
			// Close feedback form or show/hide popup if clicked outside and add a click on a Activate button of Theme.
			if (
				e.target.classList.contains( 'show_popup' ) &&
				e.target.closest( '.uds-feedback-form--wrapper' )
			) {
				this._hidePopup();
			} else if ( e.target.classList.contains( 'activate' ) ) {
				this.deactivateUrl = e.target.href;

				// Don't show for Child Themes if parent theme is active & Parent Theme if child theme is active.
				if (
					-1 !==
					this.deactivateUrl.indexOf(
						`stylesheet=${ udsVars?._current_theme }-child`
					) ||
					-1 !==
					this.deactivateUrl.indexOf(`stylesheet=${udsVars?._current_theme}&`)
				) {
					return;
				}

				e.preventDefault();
				this._showPopup( udsVars?._current_theme );
			}
		},

		/**
		 * Initializes the feedback popup by caching elements and binding events.
		 */
		_init() {
			this._cacheElements();
			this._bind();
		},

		/**
		 * Binds event listeners to various elements to handle user interactions.
		 */
		_bind() {
			const self = this; // Store reference to the current object.

			// Open the popup when clicked on the deactivate button.
			this.deactivateButton.on( 'click', function ( event ) {
				let closestTr = $( event.target ).closest( 'tr' );
				let slug = closestTr.data( 'slug' );

				if ( self.slug.includes( slug ) ) {
					event.preventDefault();
					// Set the deactivation URL.
					self.deactivateUrl = $( event.target ).attr( 'href' );
					self._showPopup( slug );
				}
			} );

			// Close the popup on a click of Close button.
			this.closeButton.on( 'click', function ( event ) {
				event.preventDefault();
				self._hidePopup(); // Use self to refer to the UserDeactivationPopup instance.
			} );

			// Click event on radio button to change the placeholder of textarea.
			this.radioButton.on( 'click', function ( event ) {
				self._changePlaceholderText( event );
			} );

			// Combined submit and skip button actions.
			this.submitDeactivate
				.add( this.skipButton )
				.on( 'click', function ( event ) {
					event.preventDefault(); // Prevent default button action.
					self.buttonAction = $( event.target ).attr( 'data-action' );
					$( event.target ).closest( self.feedbackForm ).submit();
				} );

			this.feedbackForm.on( 'submit', function ( event ) {
				self._submitFeedback( event, self );
			} );

			document.addEventListener( 'click', function ( e ) {
				self._handleClick( e );
			} );
		},
	};

	$( function () {
		UserDeactivationPopup._init();
	} );
} )( jQuery );
