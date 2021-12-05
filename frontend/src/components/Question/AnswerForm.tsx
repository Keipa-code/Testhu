import React, {FC, useEffect, useState} from 'react';
import {Button, CloseButton, Col, Form, ListGroup, OverlayTrigger, Row, Tooltip} from "react-bootstrap";
import {useRootStore} from "../../RootStateContext";
import {observer} from "mobx-react-lite";

type AnswerFormProps = {
    qKey?: number,
}

const AnswerForm: FC<AnswerFormProps> = observer(({qKey}) => {
    const {questionFormStore} = useRootStore()
    const [answer, setAnswer] = useState('')
    const [visible, setVisible] = useState(false)
    const renderTooltip = (props) => (
        <Tooltip id="button-tooltip" {...props}>
            В случае, если добавлен только один вариант ответа,
            то при прохождении теста ответ пишут вручную
        </Tooltip>
    );

    useEffect(() => {
        (questionFormStore.questions[qKey].answers.length === 0) ? setVisible(false) : setVisible(true)
    }, [])

    // Добавить ограничение на максимальное количество вариантов - 5
    const handleAddAnswer = (e: React.MouseEvent<HTMLInputElement>) => {
        e.preventDefault()
        questionFormStore.addAnswer(qKey, answer)
        setAnswer('')
        setVisible(true)
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setAnswer(e.target.value)
    };

    const handleRemoveAnswer = (aKey) => {
        setVisible(questionFormStore.removeAnswer(qKey, aKey))
    }

    return (
        <div>
            <Form.Label>Вариант ответа</Form.Label>
            <Row>
                <Col xs="10">
                    <Form.Control placeholder="Введите ответ" value={answer} onChange={handleChange}/>
                </Col>
                <Col xs="2">
                    <Button variant="primary" type="submit" onClick={handleAddAnswer}>
                        Добавить
                    </Button>
                </Col>
            </Row>
            <OverlayTrigger
                placement="right"
                delay={{show: 250, hide: 400}}
                overlay={renderTooltip}
            >
                <button className="mb-3 btn mt-1 ms-1">
                    <span className="material-icons md-dark">help</span>
                </button>
            </OverlayTrigger>
            <p className={!visible ? "visually-hidden" : ""}>
                Поставьте галочку возле правильного ответа. Можете выбрать несколько правильных ответов.
            </p>
            <ListGroup as="ol" className="mb-3">
                {questionFormStore.questions[qKey].answers.map((answer, aKey) =>
                    <ListGroup.Item as="li" key={aKey}>
                        <Row>
                            <Col md="auto">
                                <Form.Check
                                    type="checkbox"
                                    defaultChecked={answer.correct}
                                    onChange={() => {
                                        questionFormStore.answerCheckedChange(qKey, aKey)
                                    }}
                                />
                            </Col>
                            <Col>
                                <p>{answer.text}</p>
                            </Col>
                            <Col md="auto">
                                <CloseButton
                                    onClick={() => {
                                        handleRemoveAnswer(aKey)
                                    }}
                                />
                            </Col>
                        </Row>
                    </ListGroup.Item>
                )}
            </ListGroup>
        </div>
    )
})

export default AnswerForm;