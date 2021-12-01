import React, {FC, useEffect} from 'react';
import {useRootStore} from "../../RootStateContext";
import {ActionMeta, OnChangeValue} from "react-select";
import {TagOption} from "./TagsFormStore";
import CreatableSelect from "react-select/creatable";
import {observer} from "mobx-react-lite";
import {Button} from "react-bootstrap";
import {NewTestStore} from "../../pages/NewTest/NewTestStore";


interface TagsFormProps {
    addTags?: NewTestStore["addTags"];
}
const TagsForm: FC<TagsFormProps> = observer(({addTags}) => {
    const {tagsFormStore} = useRootStore()

    useEffect(() => {
        tagsFormStore.fetchTags()
    }, [])

    const handleChange = (
        newValue: OnChangeValue<TagOption, true>,
        actionMeta: ActionMeta<TagOption>
    ) => {
        const id = (newValue.length - 1)
        tagsFormStore.addSelectedTags(newValue[id], actionMeta.action)
            .then(() => {
                addTags(id)
            })
    }

    return (
        <div>
            <CreatableSelect
                isMulti
                onChange={handleChange}
                options={tagsFormStore.tags}
            />
            <Button variant="success" onClick={() => {
                console.log(tagsFormStore.selectedTags)
            }}>Фетч</Button>
        </div>
    );
});

export default TagsForm;