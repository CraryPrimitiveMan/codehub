<?php

class TagEditPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser, TestHelper;

    /** @test */
    public function it_can_edit_tags()
    {
//        $this->actingAs($this->user)
//            ->visit(route('admin.tag.edit', 1))
//            ->type('Foo', 'title')
//            ->press('Save')
//            ->see('Success! Tag has been updated.')
//            ->see('Foo')
//            ->seeInDatabase('tags', ['title' => 'Foo']);
    }

    /** @test */
    public function it_can_delete_a_tag_from_the_database()
    {
        Auth::guard('canvas')->login($this->user);
        $this->callRouteAsUser('canvas.admin.tag.edit', 1)
            ->press('Delete Tag')
            ->see('Success! Tag has been deleted.')
            ->dontSeeTagInDatabase('tags', ['id' => 1])
            ->assertSessionMissing('errors');
    }
}
